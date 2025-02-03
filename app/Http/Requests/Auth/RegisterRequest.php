<?php

namespace App\Http\Requests\Auth;

use App\Enums\AppLoginMethod;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],

        ];

        if (config('app.app_login_method') == AppLoginMethod::EMAIL->value) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
        }

        if (config('app.app_login_method') == AppLoginMethod::PHONE->value) {
            $rules['phone'] = ['required', 'string', 'max:255', 'unique:users'];
        }
        return $rules;
    }
}
