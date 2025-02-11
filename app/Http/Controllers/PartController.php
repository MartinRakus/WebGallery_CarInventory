<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller {
    // Get Parts
    public function index() {
        return response()->json(Part::with('car')->get());
    }

    // Store new Parts
    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required|string|max:255', 'serialnumber' => 'required|string|unique:parts,serialnumber|max:255', 'car_id' => 'required|exists:cars,id']);
        return Part::create($validated);
    }
}
