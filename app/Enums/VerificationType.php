<?php

namespace App\Enums;

enum VerificationType: int
{
    case PHONE = 1;
    case EMAIL = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::PHONE => 'Telefon',
            self::EMAIL => 'E-posta',
        };
    }
}
