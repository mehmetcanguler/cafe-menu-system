<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contracts\FileRepositoryInterface;
use App\Enums\FileType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductStoreRequest;
use App\Http\Requests\Api\ProductUpdateRequest;
use App\Http\Requests\Api\SearchRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Support\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $query = Product::with([
            'files',
            'categories'
        ]);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('slug', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orwhereHas('categories', function ($query) use ($request) {
                    $query->where('slug', $request->category);
                });
        }

        if ($request->category_ids) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->whereIn('categories.id', $request->category_ids);
            });
        }

        return ApiResponse::collection(
            ProductResource::collection(
                $query->paginate(
                    $request->per_age ?? 10
                )
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->images && count($request->images) > 0) {
            foreach ($request->images as $image) {
                $this->fileRepository->store(
                    FileType::PRODUCT,
                    $image,
                    $product
                );
            }
        }

        if ($request->category_ids && count($request->category_ids) > 0) {
            $product->categories()->attach($request->category_ids);
        }

        return ApiResponse::created();

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ApiResponse::setData(
            ProductResource::make(
                $product->load([
                    'files',
                    'categories'
                ])
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->category_ids && count($request->category_ids) > 0) {
            $product->categories()->sync($request->category_ids);
        }

        return ApiResponse::updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->files->count() > 0) {
            foreach ($product->files as $file) {
                $this->fileRepository->destroy($file);
            }
        }
        $product->delete();

        return ApiResponse::deleted();
    }

    public function storeImageMultiple(Request $request, Product $product)
    {
        $request->validate([
            'images' => ['array'],
            'images.*' => ['image', 'nullable', 'mimes:png,jpg,jpeg,webp,gif', 'max:2048'],
        ]);
        
        foreach ($request->images as $image) {
            $this->fileRepository->store(
                FileType::PRODUCT,
                $image,
                $product
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
}
