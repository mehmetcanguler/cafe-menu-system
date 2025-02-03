<?php

namespace App\Support\Helpers;

class RouteApiHelper
{
    /**
     * Gelen isteğin api rotasından olup olmadığını kontrol eden fonksiyon
     * @return bool
     */
    public static function routeApi(): bool
    {
        $explode = explode('/', request()->path());
        if ($explode[0] == 'api') {
            return true;
        }

        return false;
    }
}
