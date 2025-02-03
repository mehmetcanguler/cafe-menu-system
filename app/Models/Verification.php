<?php

namespace App\Models;

use App\Enums\VerificationType;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use UserTrait, HasUuids;

    protected $fillable = [
        'user_id',
        'code',
        'hash',
        'validity_date',
        'type'
    ];

    protected function casts(): array
    {
        return [
            'validity_date' => 'datetime',
            'type' => VerificationType::class
        ];
    }
}
