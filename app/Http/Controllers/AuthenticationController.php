<?php

namespace App\Http\Controllers;

   
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Notifications\PasswordResetNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;
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
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ],
        [
            'name.required' => 'Nama wajib di isi',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Format email yang anda isikan salah, example@gmail.com',
            'password' => 'Password wajib di isi Minimal 8'
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

            Auth::login($user);

            return $this->sendResponse($success, [
                'status' => 'Success',
                'message' => 'Register Successfully!'
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
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ],
            [
                'email.required' => 'Email wajib di isi',
                'email.email' => 'Format email yang anda isikan salah, example@yahoo.com',
                'password.required' => "Password wajib di isi Minimal 8"
            ]
        );


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) { 
                $user = User::where('email', $request->email)->first();
                $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
                $success['name'] =  $user->name;
    
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Login Successfully!',
                    'data' => $success
                ]);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Terjadi kesalahan data, Silahkan cek kembali Email dan Password anda'
                ]);
            }
        } catch (\Throwable $th) { 
            info($th);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Terjadi Kesalahan Sistem, Silahkan coba beberapa saat lagi!   '
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
     * Forgot Password api
     *
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => "required|email"
        ], [
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Format email yang anda isikan salah, example@yahoo.com',
        ]);


        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $token = Str::random(10);
            $user->forceFill([
                'remember_token' => $token
            ])->save();
            $token = $user->remember_token;
            $user->sendPasswordResetNotification($token);

            return response()->json([
                'status' => 'Success',
                'message' => 'Token Reset Password berhasil di kirim, Silahkan cek Email anda untuk mendapatkannya',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'Error',
                'message' => 'Error pada saat melakukan Reset Password'
            ]);
        }
    }


    /**
     * Reset Password api
     *
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'token' => ['required'],
            'password' => ['required', 'confirmed', RulesPassword::defaults()]
        ]);


        try {
            $user = User::where('email', $request->email)->first();
            $resetPasswordToken = $request->input('token');
            $sessionToken = User::where('remember_token', $resetPasswordToken)->first();

            // Jika Token tidak Valid atau Salah
            if (!$sessionToken) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Token tidak Valid, atau Format Password salah. Pastikan memasukkan data dengan benar!'
                ], 422);
            }
            
            $user->password = Hash::make($request->password);
            $user->remember_token = null;
            $user->save();

            // Jika Token Valid atau Benar
            return response()->json([
                "status" => "Success",
                "message" => "Reset Password berhasil"
            ], 200);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'Error',
                'message' => 'Error pada saat melakukan reset password, silahkan cek ulang.'
            ]);
        }
    }

}