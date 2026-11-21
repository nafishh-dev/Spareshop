<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use Closure;
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
        $year = trim((string) $request->input('production_year'));

        $data = $request->validate([
            'brand'           => 'required|string|max:50',
            'model_name'      => [
                'required',
                'string',
                'max:100',
                function (string $attribute, mixed $value, Closure $fail) use ($year): void {
                    $modelName = trim((string) $value);
                    if ($modelName === '' || $year === '') {
                        return;
                    }
                    $exists = Motorcycle::whereRaw('LOWER(model_name) = ? AND LOWER(production_year) = ?', [
                        mb_strtolower($modelName),
                        mb_strtolower($year),
                    ])->exists();

                    if ($exists) {
                        $fail('Tipe motor "' . $modelName . '" (' . $year . ') sudah terdaftar.');
                    }
                },
            ],
            'production_year' => 'required|string|max:20',
        ]);

        $data['brand']           = trim($data['brand']);
        $data['model_name']      = trim($data['model_name']);
        $data['production_year'] = trim($data['production_year']);

        Motorcycle::create($data);

        return redirect()->route('motorcycles.index')->with('success', 'Data motor berhasil ditambahkan.');
    }

    public function update(Request $request, Motorcycle $motorcycle)
    {
        $year = trim((string) $request->input('production_year'));

        $data = $request->validate([
            'brand'           => 'required|string|max:50',
            'model_name'      => [
                'required',
                'string',
                'max:100',
                function (string $attribute, mixed $value, Closure $fail) use ($motorcycle, $year): void {
                    $modelName = trim((string) $value);
                    if ($modelName === '' || $year === '') {
                        return;
                    }
                    $exists = Motorcycle::where('id', '!=', $motorcycle->id)
                        ->whereRaw('LOWER(model_name) = ? AND LOWER(production_year) = ?', [
                            mb_strtolower($modelName),
                            mb_strtolower($year),
                        ])->exists();

                    if ($exists) {
                        $fail('Tipe motor "' . $modelName . '" (' . $year . ') sudah digunakan data motor lain.');
                    }
                },
            ],
            'production_year' => 'required|string|max:20',
        ]);

        $data['brand']           = trim($data['brand']);
        $data['model_name']      = trim($data['model_name']);
        $data['production_year'] = trim($data['production_year']);

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
