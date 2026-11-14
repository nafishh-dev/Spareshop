<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->filled('motorcycle_id')) {
            session(['selected_motorcycle_id' => $request->input('motorcycle_id')]);
        } else {
            session()->forget('selected_motorcycle_id');
        }

        $selectedMotor = session('selected_motorcycle_id')
            ? Motorcycle::find(session('selected_motorcycle_id'))
            : null;

        $query = Sparepart::with(['category', 'motorcycles']);

        if ($selectedMotor) {
            $query->whereHas('motorcycles', fn ($query) => $query->where('motorcycles.id', $selectedMotor->id));
        }

        if (! empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }

        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('part_code', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $spareparts = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('customer.katalog', compact('spareparts', 'categories', 'selectedMotor'));
    }

    public function resetMotor()
    {
        session()->forget('selected_motorcycle_id');

        return redirect()->route('katalog.index');
    }
}
