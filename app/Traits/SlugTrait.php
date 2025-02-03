<?php

namespace App\Traits;

use Auth;
use Str;

trait SlugTrait
{
    protected static function bootSlugTrait()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

}
