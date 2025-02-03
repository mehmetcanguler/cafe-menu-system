<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(string $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            /** Vue */
            return view('admin.app');
        }

        $categories = Category::whereHas('user', function ($query) use ($username) {
            $query->where('username', $username);
        })->get();

        return view('client.categories.index')
            ->with('categories', $categories)
            ->with('user', $user);
    }
}
