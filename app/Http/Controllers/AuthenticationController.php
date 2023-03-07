<?php

namespace App\Http\Controllers;

   
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
   

class AuthenticationController extends BaseController

{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
       
            return $this->sendResponse($success, [
                'status' => 'Success',
                'message' => 'User Register Successfully!'
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
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) { 
                $user = User::where('email', $request->email)->first();
                $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
                $success['name'] =  $user->name;
    
                return $this->sendResponse($success, [
                    'status' => 'Success',
                    'message' => 'User Login Successfully!'
                ]);
            }
        } catch (\Throwable $th) { 
            info($th);

            return $this->sendError('Unauthorised.', [
                'error' => 'Unauthorised'
            ]);
        } 
    }


    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'Logout Successfully!'
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
     * Reset Password api
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
            
            return response()->json([
                'status' => 'Success',
                'message' => 'Link for Reset Password has send in your Email!'
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
     * Update Password api
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);

            $user = User::findOrFail($id);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'The provided password does not match your current password.'], 422);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'Your password has been updated.']);
        } catch (\Throwable $th) {
            info($th);

            return response()->json(['error' => 'An error occurred while Updating your Password!'], 500);
        }
    }

}