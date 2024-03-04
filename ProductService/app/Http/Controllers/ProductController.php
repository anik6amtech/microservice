<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductById($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response(['product' => $product], 200);
        } else {
            return response(['error' => 'Product not found'], 404);
        }
    }
}
