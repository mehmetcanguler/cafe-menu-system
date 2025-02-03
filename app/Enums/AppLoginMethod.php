<?php

namespace App\Enums;

/**
 * Kayıt ol yöntemi için kullanılan doğrulama yöntemini temsil eden enum (eposta,sms,null)
 */
enum AppLoginMethod: string
{
    case EMAIL = "EMAIL";
    case PHONE = "PHONE";

    public function getLabel(): string
    {
        return match ($this) {
            self::EMAIL => 'Email',
            self::PHONE => 'Phone',
        };
    }
}
