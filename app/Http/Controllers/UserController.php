<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);

        return response()->json(['data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);
        $validatedData['password'] = bcrypt($request->password);

        try {
            $user = User::create($validatedData);

            return response()->json(['data' => $user]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json(['message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(['data' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        try {
            $user->update($validatedData);
            return response()->json(['data' => $user]);
        } catch (\Throwable $th) {
            info($th);
            return response()->json(['message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
    
            return response()->json(['message' => 'User Deleted Successfully!'], 204);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json(['message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!']);
        }
    }
}
