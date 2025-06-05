<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    private CategoryService $CategoryService;

    public function __construct(
        CategoryService $CategoryService
    ) {
        $this->CategoryService = $CategoryService;
    }

    public function index()
    {
        $categories = $this->CategoryService->getCategories();

        return CategoryResource::collection($categories)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
