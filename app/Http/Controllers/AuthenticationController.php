<?php

namespace App\Http\Controllers;

   
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
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
            Password::sendResetLink(
                $request->only('email')
            );

            return response()->json([
                'status' => 'Success',
                'message' => 'Link reset password berhasil di kirim',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'Error',
                'message' => 'Error pada saat melakukan reset password'
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        try {

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    $user->tokens()->delete();

                    event(new PasswordReset($user));
                }
            );

            // Jika token tidak valid atau kadaluarsa
            if ($status == Password::INVALID_TOKEN) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Token tidak valid atau sudah kadaluarsa.'
                ], 422);
            }

            if ($status == Password::PASSWORD_RESET) {
                return response()->json([
                    "status" => "Success",
                    "massage" => "Reset password berhasil"
                ], 200);
            }
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'Error',
                'message' => 'Error pada saat melakukan reset password, silahkan cek ulang.'
            ]);
        }
    }
}