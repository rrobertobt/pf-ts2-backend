<?php

namespace App\Http\Controllers;

use App\Models\Niche;
use App\Models\NicheState;
use App\Models\Occupant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OccupantsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $query = Occupant::with('gender', 'currentNiche');

    if (request()->has('first_name')) {
      $query->where('first_name', 'ilike', '%' . request()->query('first_name') . '%');
    }

    if (request()->has('last_name')) {
      $query->where('last_name', 'ilike', '%' . request()->query('last_name') . '%');
    }
    if (request()->has('dpi')) {
      $query->where('dpi', 'ilike', '%' . request()->query('dpi') . '%');
    }

    if (request()->has('gender_id')) {
      $query->where('gender_id', request()->query('gender_id'));
    }

    if (request()->has('death_date')) {
      $query->where('death_date', request()->query('death_date'));
    }

    $perPage = request()->query('per_page', 10);

    $occupants = $query->orderBy('id')->paginate($perPage);
    return response()->json($occupants);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'date_of_birth' => 'required|date',
      'birth_location' => 'nullable|string|max:255',
      'dpi' => 'nullable|string|max:20|unique:occupants,dpi',
      'death_date' => 'required|date',
      'death_location' => 'nullable|string|max:255',
      'death_cause' => 'nullable|string|max:255',
      'observations' => 'nullable|string|max:255',
      'gender_id' => 'required|exists:genders,id',
      'current_niche_id' => 'required|exists:niches,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Error de validación',
        'errors' => $validator->errors(),
      ], 422);
    }

    try {
      // before creating the occupant, check if the niche is available and set the state_id to 'ocupado'
      $niche = Niche::find($request->current_niche_id);
      if (!$niche) {
        return response()->json([
          'message' => 'Nicho no encontrado',
        ], 404);
      }
      if ($niche->state->slug !== 'disponible') {
        return response()->json([
          'message' => 'El nicho no está disponible',
        ], 422);
      }
      // set the state_id to 'ocupado'
      $niche->state_id = NicheState::where('slug', 'ocupado')->first()->id;
      $niche->save();
      // create the occupant
      
      $occupant = Occupant::create($request->all());
      return response()->json($occupant, 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Error al crear el ocupante',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Occupant $occupant)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Occupant $occupant)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Occupant $occupant)
  {
    //
  }
}
