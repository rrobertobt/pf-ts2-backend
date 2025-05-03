<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $credentials = $request->only('email', 'password');

    try {
      if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciales invalidas, intenta de nuevo'], 401);
      }
      // set the authorization header
      $request->headers->set('Authorization', 'Bearer ' . $token);

      $user = JWTAuth::user();
      // get the user's role data too
      $user = User::with('role')->find($user->id);
      // check if the user is active
      if (!$user->is_active) {
        return response()->json(['message' => 'Usuario inactivo'], 401);
      }
      // remove password and remember_token from the user object
      unset($user->password);
      unset($user->remember_token);
      // return the user object with the token
      return response()->json(
        $user
      )->header('Authorization', 'Bearer ' . $token);
    } catch (\Throwable $e) {
      return response()->json(['error' => 'Could not create token'], 500);
    }
  }

  public function myProfile(Request $request)
  {
    try {
      // $user = JWTAuth::user();
      // return response()->json($user);
      $user = Auth::user();
      // get user from db using id to get latest data with the role relation

      $user = User::with('role')->find($user->id);
      // remove password and remember_token from the user object
      unset($user->password);
      unset($user->remember_token);
      // return the user object
      return response()->json($user);
    } catch (\Throwable $e) {
      return response()->json(['error' => 'Could not retrieve user'], 500);
    }
  }

  public function logout(Request $request)
  {
    JWTAuth::invalidate(JWTAuth::getToken());
    return response()->json(['message' => 'Successfully logged out'], 200);
  }

  // public function register(Request $request)
  // {
  //     // Implement registration logic here
  //     return response()->json(['message' => 'Registration successful']);
  // }
}
