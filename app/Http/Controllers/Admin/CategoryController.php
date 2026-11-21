<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('spareparts')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $slug = Str::slug((string) $value);
                    if ($slug === '') {
                        $fail('Nama kategori harus mengandung huruf atau angka.');
                        return;
                    }
                    if (Category::where('slug', $slug)->exists()) {
                        $fail('Nama kategori menghasilkan slug "' . $slug . '" yang sudah terdaftar.');
                    }
                },
            ],
        ]);

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);
        cache()->forget('footer_categories');

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name,' . $category->id,
                function (string $attribute, mixed $value, Closure $fail) use ($category): void {
                    $slug = Str::slug((string) $value);
                    if ($slug === '') {
                        $fail('Nama kategori harus mengandung huruf atau angka.');
                        return;
                    }
                    if (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                        $fail('Nama kategori menghasilkan slug "' . $slug . '" yang sudah digunakan kategori lain.');
                    }
                },
            ],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);
        cache()->forget('footer_categories');

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->spareparts()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memuat sparepart.');
        }

        $category->delete();
        cache()->forget('footer_categories');

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
