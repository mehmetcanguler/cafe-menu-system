<?php

namespace App\Http\Controllers\Auth;

use App\Enums\VerificationType;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Verification;
use App\Notifications\EmailVerifyNotification;
use App\Services\TwilioSdkService;
use Illuminate\Database\Eloquent\Model;

class AuthController extends Controller
{
    /**
     * Login response standartlaştırmak için kullanılan method
     */
    protected function successLogin(User $user)
    {
        $token = $user->createToken('authToken');

        return [
            'user' => UserResource::make($user),
            'token' => $token->plainTextToken,
            'success' => true,
        ];
    }
    /**
     * Doğrulama için Sms kod oluşturma gönderme işlemi yapan method
     */
    protected function phoneVerify(User $user)
    {
        $code = \App\Support\Helpers\RandomHelper::randomVerifyCode();
        $hash = \App\Support\Helpers\RandomHelper::randomHash();

        $verification = Verification::create([
            'user_id' => $user->id,
            'code' => $code,
            'validity_date' => now()->addMinutes(5),
            'hash' => $hash,
            'type' => VerificationType::PHONE,
        ]);

        dispatch(new \App\Jobs\PhoneVerifyJob($verification));

        return $verification;
    }
    /**
     * Doğrulama için Email kod oluşturma gönderme işlemi yapan method
     */
    protected function emailVerify(User $user)
    {
        $code = \App\Support\Helpers\RandomHelper::randomVerifyCode();
        $hash = \App\Support\Helpers\RandomHelper::randomHash();

        $verification = Verification::create([
            'user_id' => $user->id,
            'code' => $code,
            'validity_date' => now()->addMinutes(5),
            'hash' => $hash,
            'type' => VerificationType::EMAIL,
        ]);

        $user->notify(new EmailVerifyNotification($verification));

        return $verification;
    }
    /**
     * Sms ve Email için Çok fazla istek atılıp atılmadığının kontrolunu yapan method
     */
    public function tooManyCheck(User|Model $user, VerificationType $type)
    {
        $verificationCount = Verification::where('user_id', $user->id)
            ->where('validity_date', '>', now())
            ->where('type', $type)
            ->count();

        if ($verificationCount > 3) {
            return true;
        }

        return false;
    }
}
