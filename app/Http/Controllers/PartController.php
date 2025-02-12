<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller {
    // Get Parts
    public function index() {
        return response()->json(Part::with('car')->get());
    }

    // Store new or edit existing Parts
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serialnumber' => 'required|string|unique:parts,serialnumber,' . $request['id'] . '|max:255',
            'car_id' => 'required|exists:cars,id'
        ]); // validate received data

        $part = Part::where('id', $request['id'])->first(); // search for part in DB
        if ($part) {
            return $part->update($validated);
        } else {
            return Part::create($validated);
        } // update if exists, create if not
    }

    // Remove Part
    public function destroy(string $id) {
        return Part::where('id', $id)->delete();
    }
}
