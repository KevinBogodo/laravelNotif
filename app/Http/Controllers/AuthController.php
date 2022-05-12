<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    /**
     * 
     *  Create a new instance Auth
     * 
     */

     public function __construct()
     {
         $this->middleware('auth:api', ['except' =>['login','register']]);
     }

     public function login(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:7'
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Set token validity
        $token_validity = 24 * 60;
        $this->guard()->factory()->setTTL($token_validity);

        if(!$token = $this->guard()->attempt($validator->validated())){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
     }

     public function register(Request $request)
     {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|between:2,100',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|confirmed|min:6'
        // ]);
     }

     public function logout()
     {
        $this->guard()->logout();
        return response()->json(['message' => 'Logged out successfuly']);
     }

     public function profile()
     {
        return response()->json($this->guard()->user());
     }


     protected function respondWithToken($token) {
         return response()->json([
             'token' => $token,
             'token_type' => 'bearer',
             'token_validity' => $this->guard()->factory()->getTTL() * 60
         ]);
     }

     protected function guard() {
         return Auth::guard();
     }

}
