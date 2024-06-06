<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getAllProductsAndCategories(): JsonResponse
    {
        $products = Product::all();
        $categories = Category::all();

        if (!$products->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Returned array of products',
                'products' => $products,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Request for get products failed',
        ]);
    }
}
