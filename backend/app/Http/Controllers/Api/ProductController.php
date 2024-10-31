<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product; // Import the Product model
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $products = Product::all();
        // $products = Product::paginate(10);

        return response()->json([
            'status' => 'ok',
            'message' => 'Get all products',
            'products' => $products,
        ]);
        // return response()->json([
        //     'status' => 'ok',
        //     'message' => 'Get all products',
        //     'products' => [
        //         'current_page' => $products->currentPage(),
        //         'data' => $products->items(),
        //         'last_page' => $products->lastPage(),
        //         'total' => $products->total(),
        //         'per_page' => $products->perPage(),
        //     ],
        // ]);
    }
}
