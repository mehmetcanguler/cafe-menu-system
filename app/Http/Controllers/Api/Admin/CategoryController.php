<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contracts\FileRepositoryInterface;
use App\Enums\FileType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryStoreRequest;
use App\Http\Requests\Api\CategoryUpdateRequest;
use App\Http\Requests\Api\SearchRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ListResource;
use App\Models\Category;
use App\Support\Helpers\ApiResponse;
use File;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        public FileRepositoryInterface $fileRepository
    ) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $query = Category::with('files');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        return ApiResponse::collection(
            CategoryResource::collection(
                $query->paginate(
                    $request->per_age ?? 10
                )
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create([
            'name' => $request->name
        ]);

        if ($request->images && count($request->images) > 0) {
            foreach ($request->images as $image) {
                $this->fileRepository->store(
                    FileType::CATEGORY,
                    $image,
                    $category
                );
            }
        }

        return ApiResponse::created();
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return ApiResponse::setData(
            CategoryResource::make(
                $category->load('files')
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name
        ]);

        return ApiResponse::updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->files->count() > 0) {
            foreach ($category->files as $file) {
                $this->fileRepository->destroy($file);
            }
        }
        $category->delete();

        return ApiResponse::deleted();
    }
    public function storeImageMultiple(Request $request, Category $category)
    {
        $request->validate([
            'images' => ['array'],
            'images.*' => ['image', 'nullable', 'mimes:png,jpg,jpeg,webp,gif', 'max:2048'],
        ]);
        
        foreach ($request->images as $image) {
            $this->fileRepository->store(
                FileType::CATEGORY,
                $image,
                $category
            );
        }
       

        return ApiResponse::created();
    }
    public function destroyImage(\App\Models\File $file)
    {
        $this->fileRepository->destroy(
            $file
        );

        return ApiResponse::deleted();
    }

    public function list(SearchRequest $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return ApiResponse::setData(
            ListResource::collection(
                $query->get(['id', 'name'])
            )
        );
    }
}
