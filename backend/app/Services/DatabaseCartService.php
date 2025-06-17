<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\DB;
use App\Contracts\CartContract;
use App\Models\Cart;
use App\Models\Product;

class DatabaseCartService implements CartContract
{
    protected Guard $guard;
    protected SessionManager $session;
    protected ?Cart $cart;
    protected string $cartSessionKey;

    public function __construct(
        Guard $auth,
        SessionManager $session,
    ) {
        $this->auth = $auth;
        $this->session = $session;
        $this->load();
    }

    public function load(): void
    {
        $user = $this->auth->user();
        $sessionId = $this->session->getId();

        DB::transaction(function () use ($user, $sessionId) {
            // 1. ログインユーザーのカートを検索（優先）
            if ($user) {
                $this->cart = Cart::createOrFirst([
                    'user_id' => $user->id,
                ]);
                logInfo('Database Cart: Loaded user cart.', [
                    'user_id' => $user->id,
                    'cart_id' => $this->cart->id
                ]);

                // 2. ゲストセッションIDに紐づくカートが存在するかチェック
                $guestCart = null;
                if ($sessionId) {
                    $guestCart = Cart::where('guest_session_id', $sessionId)
                        ->whereNull('user_id')
                        ->first();
                }

                // 3. 旧セッションカート(SessionCartServiceが扱っていた形式のデータ)も取得
                $sessionCartItems = collect($this->session->get($this->cartSessionKey, []));

                // 4. マージロジック
                if ($guestCart) {
                    logInfo('Database Cart: Merging guest cart to user cart.', [
                        'guest_cart_id' => $guestCart->id,
                        'user_cart_id' => $this->cart->id,
                    ]);
                    $this->mergeCarts($guestCart, $this->cart);
                }

                // 5. 旧セッションカートの内容をDBカートにマージ
                if ($sessionCartItems) {
                    logInfo('Database Cart: Merging legacy session cart to user cart.', [
                        'session_items_count' => $sessionCartItems->count(),
                        'user_cart_id' => $this->cart->id,
                    ]);
                    $this->mergeSessionCartToDbCart($sessionCartItems, $this->cart);
                    // セッションカートはクリアする
                    $this->session->forget($this->cartSessionKey);
                }
            } else {
                // ゲストユーザーの場合
                $this->cart = Cart::where('guest_session_id', $sessionId)
                    ->whereNull('user_id')
                    ->createOrFirst([
                        'guest_session_id' => $sessionId,
                    ]);
                logInfo('Database Cart: Loaded guest cart.', [
                    'session_id' => $sessionId,
                    'cart_id' => $this->cart->id,
                ]);
            }
        });
    }

    public function add(Product $product, int $quantity = 1): void
    {
        if (!$this->cart) {
            logInfo('Database Cart: No active cart to add item to.', []);
            return;
        }

        $cartItem = $this->cart->cartItems()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $quantity->save();
            logInfo('Database Cart: Item quantity updated.', [
                'cart_item_id' => $cartItem->id,
                'new_quantity' => $cartItem->quantity
            ]);
        } else {
            $cartItem = $this->cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'prict' => $product->prict,
            ]);
            logInfo('Database Cart: New item added.', [
                'cart_item_id' => $cartItem->id,
            ]);
        }
        $this->save();
    }

    public function remove(Product $product): void
    {
        if ($this->cart) {
            $this->cart->cartItems()
                ->where('product_id', $product->id)
                ->delete();

            logInfo('Database Cart: Item removed.', [
                'product_id' => $product->id,
            ]);

            $this->save();
        }
    }

    public function update(Product $product, int $quantity): void
    {
        if ($this->cart) {
            $cartItem = $this->cart->cartItems()
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                if ($quantity <= 0) {
                    $cartItem->delete();
                    logInfo('Database Cart: Item removed due to zero quantity.', [
                        'product_id' => $product->id,
                    ]);
                } else {
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
                    logInfo('Database Cart: Item quantity updated.', [
                        'cart_item_id' => $cartItem->id,
                        'new_quantity' => $cartItem->quantity,
                    ]);
                }

                $this->save();
            }
        }
    }

    public function getCartItems(): Collection
    {
        return $this->cart ?
            $this->cart->cartItems()
                ->with('product')
                ->get()
                ->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'name' => $item->product->name ?? 'Unknown Product',
                        'price' => $item->price,
                        'quantity' => (int) $item->quantity,
                        'image_url' => $item->product->image_url ?? null,
                        'stock' => ($item->price * $item->quantity),
                    ];
                }) : collect([]);
    }

    public function getTotal(): float
    {
        return $this->cart ?
            $this->cart->cartItems()
                ->sum(function($item) {
                    return $item->price * $item->quantity;
                }): 0.0;
    }

    public function clear(): void
    {
        if ($this->cart) {
            $this->cart->cartItems()->delete();
            $this->save();
            logInfo('Database Cart: Cart cleared.', []);
        }
    }

    public function save(): void
    {
        if ($this->cart) {
            $this->cart->touch();
            logInfo('Database Cart: Cart header touched.', [
                'cart_id' => $this->cart->id,
            ]);
        }
    }

    protected function mergeCarts(Cart $guestCart, Cart $destinationCart): void
    {
        $guestCart->load('items.product');

        foreach ($guestCart->cartItems as $guestCartItem) {
            $destinationItem = $destinationCart->ccartItems()
                ->where('product_id', $guestCartItem->product_id)
                ->first();

            if ($destinationItem) {
                $destinationItem->quantity += $guestCartItem->quantity;
                $destinationItem->save();
            } else {
                $guestCartItem->cart_id = $destinationCart->id;
                $guestCartItem->save();
            }
        }

        $guestCart->delete();
        logInfo('Database Cart: Merged and deleted source cart.', [
            'guest_cart_id' => $guestCart->id,
        ]);
    }

    protected function mergeSessionCartToDbCart(Collection $sessionItems, Cart $destinationCart): void
    {
        foreach ($sessionItems as $sessionItem) {
            $destinationItem = $destinationCart->cartItems()
                ->where('product_id', $sessionItem['product_id'])
                ->first();

            if ($destinationItem) {
                $destinationItem->quantity += $sessionItem['quantity'];
                $destinationItem->save();
            } else {
                $destinationCart->CartItems()->create([
                    'product_id' => $sessionItem['product_id'],
                    'quantity' => $sessionItem['quantity'],
                    'price' => $sessionItem['price'],
                ]);
            }
        }

        logInfo('Database Cart: Merged session items to DB cart.', [
            'db_cart_id' => $destinationCart->id,
        ]);
    }
}
