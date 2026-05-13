<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $shopId = $this->mainShop()->id;
        $request = request();

        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = $request->per_page  ?? 15;
        $skip = ($page * $perPage) - $perPage;

        $categories = CategoryRepository::query()->where('shop_id', $shopId)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->latest('id');

        $total = $categories->count();

        return $this->json("All Categories", [
            'total' => $total,
            'categories' => CategoryResource::collection($categories->skip($skip)->take($perPage)->get()),
        ]);
    }

    public function show(Category $category)
    {
        return $this->json("Category", [
            'category' => CategoryResource::make($category),
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $category = CategoryRepository::storeByRequest($request);

        return $this->json("Category Created Successfully", [
            'category' => CategoryResource::make($category)
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category = CategoryRepository::updateByRequest($request, $category);

        return $this->json("Category Updated Successfully", [
            'category' => CategoryResource::make($category)
        ]);
    }

    public function destroy(Category $category)
    {
        $thumbnail = $category->thumbnail;
        if ($thumbnail && Storage::exists($thumbnail->src)) {
            Storage::delete($thumbnail->src);
        }
        $category->delete();
        return $this->json("Category Deleted Successfully");
    }

    public function parentCategory(){
        $categories = CategoryRepository::query()->where('parent_id', null)->get();
        return $this->json("Parent Categories", [
            'categories' => CategoryResource::collection($categories)
        ]);
    }
}
