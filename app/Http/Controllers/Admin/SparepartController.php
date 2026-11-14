<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $query = Sparepart::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('part_code', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $spareparts = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.spareparts.index', compact('spareparts', 'categories'));
    }

    public function create()
    {
        $categories  = Category::all();
        $motorcycles = Motorcycle::all();
        return view('admin.spareparts.create', compact('categories', 'motorcycles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'part_code'    => 'required|string|max:50|unique:spareparts,part_code',
            'name'         => 'required|string|max:255',
            'brand'        => 'required|string|max:100',
            'price'        => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|max:2048',
            'motorcycles'  => 'nullable|array',
            'motorcycles.*'=> 'exists:motorcycles,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('spareparts', 'public');
        }

        $sparepart = Sparepart::create($data);
        $sparepart->motorcycles()->sync($request->input('motorcycles', []));

        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil ditambahkan.');
    }

    public function edit(Sparepart $sparepart)
    {
        $categories  = Category::all();
        $motorcycles = Motorcycle::all();
        return view('admin.spareparts.edit', compact('sparepart', 'categories', 'motorcycles'));
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'part_code'    => 'required|string|max:50|unique:spareparts,part_code,' . $sparepart->id,
            'name'         => 'required|string|max:255',
            'brand'        => 'required|string|max:100',
            'price'        => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|max:2048',
            'motorcycles'  => 'nullable|array',
            'motorcycles.*'=> 'exists:motorcycles,id',
        ]);

        if ($request->hasFile('image')) {
            if ($sparepart->image) Storage::disk('public')->delete($sparepart->image);
            $data['image'] = $request->file('image')->store('spareparts', 'public');
        }

        $sparepart->update($data);
        $sparepart->motorcycles()->sync($request->input('motorcycles', []));

        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function destroy(Sparepart $sparepart)
    {
        if ($sparepart->image) Storage::disk('public')->delete($sparepart->image);
        $sparepart->delete();

        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil dihapus.');
    }
}
