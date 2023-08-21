<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile'  => 'required|numeric',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors(),'success' =>false],422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);
        if($user){
            $credentials = $request->only('email', 'password');
     
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return response()->json([
                    'message' => 'User created successfully',
                    'token' => $user->createToken('auth_token')->plainTextToken,
                    'user_id'=>auth()->id(),
                    'type' => 'bearer',
                    'success' =>true
                ]);
            }
        }
        }
        catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => __('server error'),
                'success' =>false
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response(['error' => $validator->errors(),'success' =>false],401);
        }
        $credentials = $request->only('email', 'password');
     
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'user' => new UserResource($user),
                'authorization' => [
                    'token' => $user->createToken('auth_token')->plainTextToken,
                    'type' => 'bearer',
                    'success' =>true
                ]
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
        catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => __('server error'),
                'success' =>false
            ], 500);
        }
    }
    
    public function logout(Request $request)
    {
        try {
        if(!empty(Auth::user()->currentAccessToken())){
            if($request->user()->currentAccessToken()->delete()){
            return response()->json([
                'message' => __('Successfully logged out'),
                'success' =>true
            ],200);
          }
          else {
            return  response()->json(['message'=>__('Logout failed'),'success' =>false],400);
          }
        }
        else {
            return  response()->json([ 'valid' => auth()->check(),'success' =>false],400);
        }
      }
        catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => __('server error'),
                'success' =>false
            ], 500);
        }
    }

    public function userDetails() {

        try {
            if(Auth::check()) {
                return new UserResource(User::find(auth()->id()));
            }
            else {
                return response()->json([
                    'message' => 'unauthenticated',
                ], 401);
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => __('server error')
            ], 500);
        }
    }
}

