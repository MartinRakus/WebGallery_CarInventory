<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller {
    // Get Cars
    public function index() {
        return response()->json(Car::with('parts')->get());
    }

    // Store new or edit existing Car
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|required_if:is_registered,true|string|unique:cars,registration_number,' . $request['id'] . '|max:255',
            'is_registered' => 'boolean'
        ]); // validate received data

        $car = Car::where('id', $request['id'])->first(); // search for car in DB
        if ($car) {
            return $car->update($validated);
        } else {
            return Car::create($validated);
        } // update if exists, create if not
    }

    // Remove Car
    public function destroy(string $id) {
        return Car::where('id', $id)->delete();
    }
}
