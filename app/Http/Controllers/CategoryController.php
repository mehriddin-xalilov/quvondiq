<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-categories')->only(['index', 'show']);
        $this->middleware('permission:create-categories')->only(['create', 'store']);
        $this->middleware('permission:edit-categories')->only(['edit', 'update']);
        $this->middleware('permission:delete-categories')->only(['destroy']);
    }

    public function index()
    {
        $categories = Category::with('parent')->orderBy('sort_order')->orderBy('name')->paginate(12);
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $products = $category->products()->with(['category', 'stock'])->paginate(12);
        return view('categories.show', compact('category', 'products'));
    }

    public function create()
    {
        $parents = Category::where('parent_id', null)->orderBy('name')->get();
        return view('categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '_' . uniqid() . '.webp';
            $path = 'categories/' . $filename;

            // Ensure directory exists
            if (!Storage::disk('public')->exists('categories')) {
                Storage::disk('public')->makeDirectory('categories');
            }

            // Read image and convert to WebP
            $image = Image::read($file);
            $image->toWebp(80)->save(storage_path('app/public/' . $path));

            $validated['icon'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Kategoriya muvaffaqiyatli yaratildi');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('parent_id', null)->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            $file = $request->file('icon');
            $filename = time() . '_' . uniqid() . '.webp';
            $path = 'categories/' . $filename;

            if (!Storage::disk('public')->exists('categories')) {
                Storage::disk('public')->makeDirectory('categories');
            }

            $image = Image::read($file);
            $image->toWebp(80)->save(storage_path('app/public/' . $path));

            $validated['icon'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Kategoriya muvaffaqiyatli yangilandi');
    }

    public function destroy(Category $category)
    {
        // \Illuminate\Support\Facades\Log::info('Deleting category: ' . $category->id);

        if ($category->products()->count() > 0) {
            return back()->with('error', 'Bu kategoriyada mahsulotlar bor. Oldin ularni o\'chiring yoki boshqa kategoriyaga o\'tkazing.');
        }

        if ($category->children()->count() > 0) {
            return back()->with('error', 'Bu kategoriyaning ost-kategoriyalari mavjud. Oldin ularni o\'chiring.');
        }

        if ($category->icon && Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategoriya o\'chirildi');
    }

    public function export(Request $request)
    {
        $query = Category::with('parent');

        $isFiltered = false;

        // Note: CategoryController index doesn't seem to have extensive server-side filtering 
        // implemented in the controller (it just paginates all). 
        // However, if we want to support search/filter in export, we should implement it here 
        // matching the view's expectation or future implementation.
        // The view `categories.index` seems to imply search might be desired, 
        // but looking at `productTable` in products/index.blade.php, it uses server-side filtering.
        // `categories/index.blade.php` does NOT seem to have a server-side search form in the controller code I viewed.
        // Wait, line 23 of CategoryController: 
        // $categories = Category::with('parent')->orderBy('sort_order')->orderBy('name')->paginate(12);
        // It does NOT have filtering logic like ProductController.
        // So I will just implement basic export for now, or check if I should add filtering.
        // The user said "filter qilsa filterlangan datani".
        // Use `request` to check if any filter params exist, even if index() doesn't currently use them,
        // or effectively implement filtering for the export at least.

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $isFiltered = true;
        }

        if ($isFiltered) {
            $categories = $query->orderBy('sort_order')->orderBy('name')->get();
        } else {
            $categories = $query->orderBy('sort_order')->orderBy('name')->take(50)->get();
        }

        return Excel::download(new CategoriesExport($categories), 'categories.xlsx');
    }
}
