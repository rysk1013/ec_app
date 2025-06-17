<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\http\Requests\Cart\AddItemRequest;
use App\Http\Resources\CartItemResource;
use App\Contracts\CartContract;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    protected CartContract $CartService;
    protected ProductService $ProductService;

    public function __construct(
        CartContract $CartService,
        ProductService $ProductService,
    ) {
        $this->CartService = $CartService;
        $this->ProductService = $ProductService;
    }

    public function addItem(AddItemRequest $request): JsonResponse
    {
        $product = $this->ProductService->getProductById($request->product_id);

        if (is_null($product)) {
            return response()->json([
                'message' => 'Product not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->CartService->add($product, $request->quantity);

        return response()->json([
            'message' => 'Product added to cart successfully.',
            'cart_items' => CartItemResource::collection($this->CartService->getCartItems()),
            'cart_total' => $this->CartService->getTotal(),
        ], Response::HTTP_OK);
    }
}
