<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;

class ProductController extends Controller
{
    public function index(string $username, string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)
            ->whereHas('user', function ($query) use ($username) {
                $query->where('username', $username);
            })->first();
        $user = User::where('username', $username)->first();

        if (!$user || !$category) {
            /** Vue */
            return view('admin.app');
        }

        $products = $category->products;
        return view('client.products.index')
            ->with('products', $products)
            ->with('user', $user)
            ->with('category', $category);
    }
}
