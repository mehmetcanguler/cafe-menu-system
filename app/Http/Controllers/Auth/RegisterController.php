<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AppLoginMethod;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\EnumResource;
use App\Models\User;
use App\Support\Helpers\ApiResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends AuthController
{
    /**
     * config('app.app_login_method') Kayıt ol yöntemi için kullanılan config değerini temsil ediyor (eposta,sms,null)
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'phone' => $request->phone,
        ]);

        $verification = null;

        if (config('app.app_login_method') == AppLoginMethod::EMAIL->value) {
            $verification = $this->emailVerify($user);
        } elseif (config('app.app_login_method') == AppLoginMethod::PHONE->value) {
            $verification = $this->phoneVerify($user);
        } else {
            return ApiResponse::setData(
                $this->successLogin(
                    $user
                )
            );
        }

        if (!$verification) {
            return ApiResponse::error('Bir sorun oluştu');
        }

        return ApiResponse::setData([
            'message' => 'Başarıyla kod gönderildi',
            'hash' => $verification->hash,
            'type' => EnumResource::make($verification->type),
        ]);
    }
}
