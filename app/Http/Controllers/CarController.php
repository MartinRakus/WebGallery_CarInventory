<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller {
    // Get Cars
    public function index() {
        return response()->json(Car::with('parts')->get());
    }

    // Store new Car
    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required|string|max:255', 'registration_number' => 'nullable|required_if:is_registered,true|string|unique:cars,registration_number|max:255', 'is_registered' => 'boolean']);
        return Car::create($validated);
    }
}
