<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use App\Traits\SlugTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([UserScope::class])]
class Category extends Model
{
    use HasFactory, UserTrait, HasUuids, SlugTrait;

    protected $fillable = [
        'name',
        'user_id',
        'slug',
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'morphable');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
