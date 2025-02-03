<?php

namespace App\Repositories;

use App\Contracts\FileRepositoryInterface;
use App\Enums\FileType;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class FileRepository implements FileRepositoryInterface
{
    public function index(Request $request, Model $model): LengthAwarePaginator
    {
        $query = $model->files();

        if ($request->search) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('path', 'like', '%'.$request->search.'%')
                    ->orWhere('original_name', 'like', '%'.$request->search.'%')
                    ->orWhere('extension', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->created_user_id) {
            $query->where('created_user_id', $request->created_user_id);
        }

        return $query->paginate($request->per_page ?? 10);

    }

    public function store(FileType $fileType, UploadedFile $file, Model $model): File
    {
        $name = $fileType->getLabel();
        $originalName = '.'.$file->getClientOriginalExtension();
        $fileName = time().Str::random(10).Str::random(25).$originalName;
        $filePath = "uploads/files/$name/".$fileName;
        $file->storeAs('/', $filePath);

        $file = File::create([
            'path' => $filePath,
            'type' => $fileType,
            'extension' => $file->extension(),
            'name' => $fileName,
            'morphable_id' => $model->id,
            'morphable_type' => $model::class,
            'original_name' => $file->getClientOriginalName(),
        ]);

        return $file;
    }

    public function destroy(File $file): bool
    {
        $filePath = storage_path('app/public/'.$file->path);
        if (file_exists($filePath)) {
            \File::delete($filePath);
        }

        return $file->delete();
    }

}
