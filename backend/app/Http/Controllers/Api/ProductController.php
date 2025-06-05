<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\http\Resources\ProductResource;
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

    public function index()
    {
        $products = $this->ProductService->getProducts();

        return ProductResource::collection($products)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
