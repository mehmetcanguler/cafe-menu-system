<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\Helpers\ApiResponse;
use Auth;
use Illuminate\Http\Request;

class LogoutController extends AuthController
{
    public function logout()
    {
        Auth::user()->tokens()->delete();

        return ApiResponse::setData([
            'success' => true
        ]);
    }
}
