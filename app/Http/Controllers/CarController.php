<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller {
    // Get the Car which the Part belongs to
    public function index() {
        return response()->json(Car::with('parts')->get());
    }

    // List all Parts belonging to a Car
    public function show($id) {
        $car = Car::with('parts')->findOrFail($id);
        return response()->json($car);
    }

    public function create() {
        return view('cars.create');
    }
    
    // Store new Car
    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required', 'registration_number' => 'required_if:is_registered, true', 'is_registered' => 'boolean']);

        Car::create($validated);
        return redirect()->route('cars.index');
    }
}
