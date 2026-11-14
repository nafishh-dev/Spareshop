<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use Illuminate\Http\Request;

class MotorcycleController extends Controller
{
    public function index()
    {
        $motorcycles = Motorcycle::withCount('spareparts')->latest()->paginate(10);
        return view('admin.motorcycles.index', compact('motorcycles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand'           => 'required|string|max:50',
            'model_name'      => 'required|string|max:100',
            'production_year' => 'required|string|max:20',
        ]);

        Motorcycle::create($data);

        return redirect()->route('motorcycles.index')->with('success', 'Data motor berhasil ditambahkan.');
    }

    public function update(Request $request, Motorcycle $motorcycle)
    {
        $data = $request->validate([
            'brand'           => 'required|string|max:50',
            'model_name'      => 'required|string|max:100',
            'production_year' => 'required|string|max:20',
        ]);

        $motorcycle->update($data);

        return redirect()->route('motorcycles.index')->with('success', 'Data motor berhasil diperbarui.');
    }

    public function destroy(Motorcycle $motorcycle)
    {
        if ($motorcycle->spareparts()->exists()) {
            return back()->with('error', 'Motor tidak dapat dihapus karena masih terhubung dengan beberapa sparepart.');
        }

        $motorcycle->delete();

        return redirect()->route('motorcycles.index')->with('success', 'Data motor berhasil dihapus.');
    }
}
