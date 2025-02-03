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
class Product extends Model
{
    use HasFactory, HasUuids, UserTrait, SlugTrait;

    protected $fillable = [
        'name',
        'user_id',
        'slug',
        'description',
        'price'
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'morphable');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getCategoryIdsAttribute()
    {
        return $this->categories->pluck('id')->toArray();
    }
}
