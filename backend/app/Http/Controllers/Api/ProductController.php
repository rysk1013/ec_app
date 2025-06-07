<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductCollection;
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

        return (new ProductCollection($products))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
