<?php
/**
 * Manages user operations for Bike records.
 *
 * This controller handles the creation and updating of user-owned bikes,
 * including image processing and storage. It also governs the status of a bike
 * (e.g., reporting it stolen or recovered) with secure authorisation checks
 * allowing only bike owners or administrators to make status changes.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bike;
use Illuminate\Support\Facades\Storage;

class BikeController extends Controller
{
    // Method to store a new bike in the database
    public function store(Request $request)
    {
        // 1. Validate the incoming bike data and image rules
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
            'bike_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // 2. If an image was uploaded, store it publicly and map the path to the database column
        if ($request->hasFile('bike_image')) {
            $validated['img_path'] = $request->file('bike_image')->store('bikes', 'public');
            unset($validated['bike_image']); // Remove the raw file from the validated array
        }

        // 3. Create the bike record associated with the currently authenticated user
        $request->user()->bikes()->create($validated);

        // 4. Return to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Bike registered successfully!');
    }

    // Method to update an existing bike in the database
    public function update(Request $request, Bike $bike)
    {
        // 1. Ensure the authenticated user actually owns this bike
        if ($bike->user_id !== $request->user()->id) {
            abort(403);
        }

        // 2. Validate the updated data
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
            'bike_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // 3. Handle image replacement if a new image is provided
        if ($request->hasFile('bike_image')) {
            // Delete the old image from storage to prevent orphaned files
            if ($bike->img_path) {
                Storage::disk('public')->delete($bike->img_path);
            }

            $validated['img_path'] = $request->file('bike_image')->store('bikes', 'public');
            unset($validated['bike_image']);
        }

        // 4. Update the record and redirect
        $bike->update($validated);

        return redirect()->route('dashboard')->with('success', 'Bike updated successfully!');
    }

    // Method to update the status of a bike
    public function updateStatus(Request $request, Bike $bike)
    {
        // 1. Authorise the action: Allow if the user owns the bike OR if they are an admin
        if ($bike->user_id !== $request->user()->id) {
            if (!$request->user()->hasRole(\App\Enums\UserRole::ADMIN)) {
                abort(403);
            }
        }
        
        // 2. Validate the status state and optional location
        $validated = $request->validate([
            'status'        => 'required|string|in:active,stolen,sold,recovered',
            'last_location' => 'nullable|string|max:255',
        ]);

        // 3. Automatically manage the 'stolen_at' timestamp and location details based on status
        if ($validated['status'] === 'stolen') {
            $validated['stolen_at'] = now();
        } else {
            // Clear these fields if the bike is recovered/active/sold
            $validated['stolen_at'] = null;
            $validated['last_location'] = null;
        }

        // 4. Update the database and return a JSON payload for the frontend
        $bike->update($validated);

        return response()->json([
            'success'       => true,
            'status'        => $bike->status,
            'last_location' => $bike->last_location,
            'stolen_at'     => $bike->stolen_at ? $bike->stolen_at->format('d M Y H:i') : null,
        ]);
    }
}

