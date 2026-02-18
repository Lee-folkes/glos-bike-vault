<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bike;

class BikeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nickname'    => 'required|string|max:255',
            'mpn'         => 'required|string|max:255',
            'brand'       => 'required|string|max:255',
            'model'       => 'required|string|max:255',
            'type'        => 'required|string|max:255',
            'wheel_size'  => 'required|integer',
            'colour'      => 'required|string|max:255',
            'num_gears'   => 'required|integer',
            'brake_type'  => 'required|string|max:255',
            'suspension'  => 'required|string|max:255',
            'gender'      => 'required|string|max:255',
            'age_group'   => 'required|string|max:255',
        ]);

        $request->user()->bikes()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Bike registered successfully!');
    }
}
