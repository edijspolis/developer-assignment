<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\User;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Electricity price API",
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Get authentication token",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=422, description="The provided credentials are incorrect.")
     * )
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return response()->json([
            'token' => $user->createToken($request->email)->plainTextToken
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/logout",
     *     summary="Destroy authentication tokens",
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function logout(Request $request) {
        Auth::user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
