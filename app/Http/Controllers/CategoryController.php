<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksResource;
use App\Http\Resources\CategoryResource;
use App\Models\BookModel;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CategoryModel::all();
        $categoriesResource = CategoryResource::collection($categories);
        return $this->sendResponse($categoriesResource, 'Successfully get Categories');
    }
}
