<?php

namespace App\Contracts;

use App\Enums\FileType;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

interface FileRepositoryInterface
{
    public function index(Request $request, Model $model): LengthAwarePaginator;

    public function store(FileType $FileType, UploadedFile $file, Model $model): File;

    public function destroy(File $File): bool;
}
