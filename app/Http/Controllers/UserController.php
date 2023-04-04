<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

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
        ],
        [
            'name.required' => 'Nama wajib di isi',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Format email yang anda isikan salah, example@gmail.com'
        ]);
        $validatedData['password'] = bcrypt($request->password);


        try {
            $user = User::create($validatedData);

            return response()->json([
                'status' => 'Success',
                'message' => 'User Created Successfully!',
            ]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();
        return response()->json([
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ],
        [
            'name.required' => 'Nama wajib di isi',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Format email yang anda isikan salah, example@gmail.com'
        ]);


        try {
            $user->update($validatedData);

            return response()->json([
                'status' => 'Success',
                'message' => 'User Updated Successfully!',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'User Deleted Successfully!'
            ]);
        } catch (\Throwable $th) {
            info($th);
            
            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }

    public function profile(Request $request, User $name)
    {
        // Validate Request //
        $data = $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|email',
                'tanggal_lahir' => 'date',
                'jenis_kelamin' => 'string',
                'alamat' => 'max:255'
            ]
        );
        $data['biodata'] = $request->biodata;
        

        try {
            $users = Auth::user();
            $findUser = User::find($users->id);
            $findUser->update($data);

            return response()->json([
                'status' => 'Success',
                'message' => 'Profile Updated Successfully!',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!'
            ]);
        }
    }
}
