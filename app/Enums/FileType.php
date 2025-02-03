<?php

namespace App\Enums;

enum FileType: int
{
    case CATEGORY = 1;
    case PRODUCT = 2;

    public function getLabel()
    {
        return match ($this) {
            self::CATEGORY => 'category',
            self::PRODUCT => 'product',
        };
    }
}
