<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'stock');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->status) {
            if ($request->status === 'active')
                $query->where('is_active', true);
            if ($request->status === 'inactive')
                $query->where('is_active', false);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('products.partials.table-body', compact('products'))->render(),
                'pagination' => $products->links('pagination::tailwind')->toHtml(), // Or use a custom pagination view
                'total' => $products->total(),
            ]);
        }

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'unit' => 'required|in:kg,dona,qop,litr',
            'min_stock_level' => 'required|numeric|min:0',
            'optimal_stock_level' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Mahsulot muvaffaqiyatli yaratildi');
    }

    public function edit(Product $product)
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $product->id . '|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'unit' => 'required|in:kg,dona,qop,litr',
            'min_stock_level' => 'required|numeric|min:0',
            'optimal_stock_level' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        // Handle is_active checkbox (it's not present in request if unchecked)
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Mahsulot yangilandi');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Mahsulot muvaffaqiyatli o‘chirildi');
    }

    public function export(Request $request)
    {
        $query = Product::with('category', 'stock');

        $isFiltered = false;

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
            $isFiltered = true;
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
            $isFiltered = true;
        }

        if ($request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
                $isFiltered = true;
            }
            if ($request->status === 'inactive') {
                $query->where('is_active', false);
                $isFiltered = true;
            }
        }

        if ($isFiltered) {
            $products = $query->latest()->get();
        } else {
            $products = $query->latest()->take(50)->get();
        }

        return Excel::download(new ProductsExport($products), 'products.xlsx');
    }
}
