<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Product\ProductIndexRequest;
use App\Http\Requests\Product\ProductShowRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    private ProductService $ProductService;

    public function __construct(
        ProductService $ProductService
    ) {
        $this->ProductService = $ProductService;
    }

    public function index(ProductIndexRequest $request): JsonResponse
    {
        $products = $this->ProductService->getProducts($request->all());

        return ProductResource::collection($products)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show(ProductShowRequest $request): JsonResponse
    {
        $product = $this->ProductService->getProductById($request->id);

        if (is_null($product)) {
            return response()->json([
                'message' => 'Product not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
