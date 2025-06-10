<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductIndexRequest;
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
}
