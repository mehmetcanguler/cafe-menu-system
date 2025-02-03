<?php

namespace App\Traits;

use App\Models\User;
use Auth;

trait UserTrait
{
    protected static function bootUserTrait()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
