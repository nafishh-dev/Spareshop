<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $motorcycles = Motorcycle::orderBy('brand')->orderBy('model_name')->get();

        return view('customer.profile', compact('user', 'motorcycles'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'motorcycle_id' => ['nullable', 'exists:motorcycles,id'],
        ]);

        $user = $request->user();
        $user->update($validated);

        if ($user->motorcycle_id) {
            session(['selected_motorcycle_id' => $user->motorcycle_id]);
        } else {
            session()->forget('selected_motorcycle_id');
        }
        session()->forget('motor_filter_cleared');

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
