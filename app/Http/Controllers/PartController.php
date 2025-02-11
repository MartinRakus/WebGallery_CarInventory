<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller {
    // Get Parts belonging to the Car
    public function index() {
        return response()->json(Part::with('car')->get());
    }
    
    // Store new Parts
    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required', 'serialnumber' => 'required']);
        return Part::create($validated);
    }
}
