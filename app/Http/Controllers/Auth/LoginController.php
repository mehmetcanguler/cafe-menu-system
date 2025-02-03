<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AppLoginMethod;
use App\Enums\VerificationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Http\Resources\UserResource;
use App\Models\Verification;
use App\Support\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Request;

class LoginController extends AuthController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return ApiResponse::error([
                'E posta adresi bulunamadı'
            ]);
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {

            if (config('app.app_login_method') == AppLoginMethod::EMAIL->value) {
                if (!$user->email_verified_at) {
                    return ApiResponse::error('Lütfen e posta adresinizi doğrulayın');
                }
            } elseif (config('app.app_login_method') == AppLoginMethod::PHONE->value) {
                if (!$user->phone_verified_at) {
                    return ApiResponse::error('Lütfen telefon numaranızı adresinizi doğrulayın');
                }
            }

            return ApiResponse::setData(
                $this->successLogin(
                    $user
                )
            );
        }

        return response()->json(['message' => 'Kullanıcı adı veya sifre yanlış'], 401);
    }

    /**
     * Apiden gelen kod ile doğrulama yapan method
     */
    public function verify(VerifyRequest $request)
    {
        $query = Verification::where('hash', $request->hash)
            ->where('code', $request->code);

        if (config('app.app_login_method') == AppLoginMethod::EMAIL->value) {
            $query->where('type', VerificationType::EMAIL);
        }

        if (config('app.app_login_method') == AppLoginMethod::PHONE->value) {
            $query->where('type', VerificationType::PHONE);
        }

        $verification = $query->first();

        if (!$verification) {
            return ApiResponse::error([
                'Kod geçersiz ya da hatalı'
            ]);
        }

        $verification->update([
            'validity_date' => null
        ]);

        $user = $verification->user;

        $verification->type == VerificationType::EMAIL ? $user->update(['email_verified_at' => now()]) : $user->update(['phone_verified_at' => now()]);

        return response()->json(['message' => 'Başarıyla giriş yaptınız'], 401);
    }

    /**
     * Tekrar e posta gönder methodu
     */

    public function sendCodetoEmailAgain(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        $type = VerificationType::EMAIL;
        if (
            $this->tooManyCheck(
                $user,
                $type

            )
        ) {
            return ApiResponse::error([
                'Çok fazla talepte bulundunuz lütfen daha sonra yeniden deneyin'
            ]);
        }

        $this->emailVerify($user);

        return ApiResponse::setData([
            'message' => 'Başarıyla kod gönderildi'
        ]);

    }
    /**
     * Tekrar sms gönder methodu
     */

    public function sendCodetoPhoneAgain(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $user = User::where('phone', $request->phone)->first();
        $type = VerificationType::PHONE;
        if (
            $this->tooManyCheck(
                $user,
                $type
            )
        ) {
            return ApiResponse::error([
                'Çok fazla talepte bulundunuz lütfen daha sonra yeniden deneyin'
            ]);
        }

        $this->phoneVerify($user);

        return ApiResponse::setData([
            'message' => 'Başarıyla kod gönderildi'
        ]);
    }

}
