<?php

namespace App\Models;

use App\Enums\FileType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasUuids;
    protected $fillable = [
        'path',
        'type',
        'extension',
        'name',
        'morphable_id',
        'morphable_type',
        'created_user_id',
        'original_name',
    ];

    protected function casts(): array
    {
        return [
            'type' => FileType::class,
        ];
    }

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute()
    {
        // if (app()->env == 'local') {
        //     return asset($this->path);
        // }

        return asset('storage/'.$this->path);
    }

    public function morphable()
    {
        return $this->morphTo();
    }
}
