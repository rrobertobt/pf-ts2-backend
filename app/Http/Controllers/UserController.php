<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)

  {
    $query = User::with('role');

    if ($request->has('first_name')) {
      $query->where('first_name', 'ilike', '%' . $request->query('first_name') . '%');
    }

    if ($request->has('last_name')) {
      $query->where('last_name', 'ilike', '%' . $request->query('last_name') . '%');
    }

    if ($request->has('email')) {
      $query->where('email', 'ilike', '%' . $request->query('email') . '%');
    }

    if ($request->has('dpi')) {
      $query->where('dpi', 'ilike', '%' . $request->query('dpi') . '%');
    }

    if ($request->has('phone')) {
      $query->where('phone', 'ilike', '%' . $request->query('phone') . '%');
    }

    if ($request->has('role_id')) {
      $query->where('role_id', $request->query('role_id'));
    }

    $users = $query->orderBy('first_name')->get();

    return response()->json($users);
  }


  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'dpi' => 'required|string|max:20|unique:users,dpi',
      'phone' => 'required|string|max:20',
      'address' => 'required|string|max:255',
      'password' => 'required|string|min:8|confirmed',
      'role_id' => 'required|exists:roles,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Error de validación',
        'errors' => $validator->errors(),
      ], 422);
    }

    $user = User::create([
      'first_name' => $request->input('first_name'),
      'last_name' => $request->input('last_name'),
      'email' => $request->input('email'),
      'dpi' => $request->input('dpi'),
      'phone' => $request->input('phone'),
      'address' => $request->input('address'),
      'password' => bcrypt($request->input('password')),
      'role_id' => $request->input('role_id'),
    ]);


    return response()->json($user, 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(User $user) {}

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(User $user) {}

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, User $user) {}

  /**
   * Deactivate the specified resource in storage.
   */
  public function destroy($user_id)
  {
    // set the is_active field to false
    $user = User::find($user_id);
    if (!$user) {
      return response()->json([
        'message' => 'Usuario no encontrado',
      ], 404);
    }

    // Check if the user is the same as the authenticated user
    if ($user->id == auth('api')->user()->id) {
      return response()->json([
        'message' => 'No es posible desactivar tu propio usuario',
      ], 403);
    }

    // Check if the user is already inactive, if so, activate it
    if (!$user->is_active) {
      $user->is_active = true;
      $user->save();
      return response()->json([
        'message' => 'Usuario activado correctamente',
        'user' => $user,
      ]);
    }

    $user->is_active = false;
    $user->save();
    return response()->json([
      'message' => 'Usuario desactivado correctamente',
      'user' => $user,
    ]);
  }
}
