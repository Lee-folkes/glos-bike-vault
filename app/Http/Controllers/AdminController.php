<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bike;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request) 
    {
        // 1. Initialize Scout search
        // If search is empty, Scout gracefully falls back to returning all records
        $stolenBikes = Bike::search($request->input('search'))
            
            // 2. Use the query() callback to apply standard Eloquent filters and eager loading
            ->query(function ($builder) use ($request) {
                $builder->with('user'); // Eager load user to prevent N+1 issues
                
                if ($request->filled('status')) {
                    $builder->where('status', $request->input('status'));
                } else {
                    // Restrict default view to only stolen and recovered bikes
                    $builder->whereIn('status', ['stolen', 'recovered']);
                }

                if ($request->filled('date_stolen')) {
                    $builder->whereDate('stolen_at', $request->input('date_stolen'));
                }
            })

            // 3. Apply Scout sorting
            ->orderBy('stolen_at', $request->input('sort') === 'oldest' ? 'asc' : 'desc')
            
            // 4. Paginate and preserve query string in links
            ->paginate(20)
            ->withQueryString();

        return view('dashboard', compact('stolenBikes'));
    }

    public function createAdmin()
    {
        return view('admin.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => UserRole::ADMIN,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Admin user created successfully.');
    }
}
