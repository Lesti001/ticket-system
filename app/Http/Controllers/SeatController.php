<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::orderBy('seat_number')->paginate(20);
        
        return view('admin.seats.index', ['seats' => $seats]);
    }

    public function create()
    {
        return view('admin.seats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'seat_number' => 'required|string|unique:seats,seat_number',
            'base_price' => 'required|integer|min:0',
        ]);

        Seat::create($validated);

        return redirect()->route('admin.seats.index')->with('status', 'Seat created successfully!');
    }

    public function edit(Seat $seat)
    {
        return view('admin.seats.edit', ['seat' => $seat]);
    }

    public function update(Request $request, Seat $seat)
    {
        $validated = $request->validate([
            'seat_number' => ['required', 'string', Rule::unique('seats')->ignore($seat->id)],
            'base_price' => 'required|integer|min:0',
        ]);

        $seat->update($validated);

        return redirect()->route('admin.seats.index')->with('status', 'Seat updated successfully!');
    }

    public function destroy(Seat $seat)
    {
        
        if ($seat->tickets()->exists()) {
            return back()->with('error', 'Cannot delete seat because tickets have been sold for it.');
        }

        $seat->delete();

        return back()->with('status', 'Seat deleted successfully!');
    }
}