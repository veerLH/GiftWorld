<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function categories(){
        return new CategoryCollection(Category::all());
    }

    public function category(Category $category){
        return new CategoryResource($category);
    }
}
