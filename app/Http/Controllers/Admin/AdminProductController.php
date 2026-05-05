<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['brand:id,name', 'category:id,name'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->brand_id, fn ($q, $id) => $q->where('brand_id', $id))
            ->when($request->category_id, fn ($q, $id) => $q->where('category_id', $id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Products/Index', [
            'products'   => ProductResource::collection($products),
            'filters'    => $request->only(['search', 'brand_id', 'category_id']),
            'brands'     => Brand::select('id', 'name')->get(),
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Form', [
            'brands'     => Brand::select('id', 'name')->get(),
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'brand_id'    => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'numeric', 'min:0.01'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['slug'] = Str::slug($validated['name'] . '-' . Str::random(4));

        if ($request->hasFile('image')) {
            $validated['image'] = '/storage/' . $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар создан');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Form', [
            'product'    => $product,
            'brands'     => Brand::select('id', 'name')->get(),
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'brand_id'    => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'numeric', 'min:0.01'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['image'] = '/storage/' . $request->file('image')->store('products', 'public');
        }

        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name'] . '-' . Str::random(4));
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар обновлён');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            $oldPath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар удалён');
    }
}
