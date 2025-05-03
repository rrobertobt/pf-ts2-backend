<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractState;
use App\Models\NicheState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $query = Contract::with(['occupant.gender', 'niche', 'representative', 'state']);

    if ($request->has('occupant_dpi')) {
      $query->whereHas('occupant', function ($q) {
        $q->where('dpi', 'ilike', '%' . request()->query('occupant_dpi') . '%');
      });
    }
    if ($request->has('representative_dpi')) {
      $query->whereHas('representative', function ($q) {
        $q->where('dpi', 'ilike', '%' . request()->query('representative_dpi') . '%');
      });
    }
    if ($request->has('niche_code')) {
      $query->whereHas('niche', function ($q) {
        $q->where('code', 'ilike', '%' . request()->query('niche_code') . '%');
      });
    }
    if ($request->has('state_id')) {
      $query->where('state_id', $request->query('state_id'));
    }
    if ($request->has('start_date')) {
      $query->where('start_date', '>=', $request->query('start_date'));
    }
    if ($request->has('end_date')) {
      $query->where('end_date', '<=', $request->query('end_date'));
    }

    $perPage = $request->query('per_page', 10);
    $contracts = $query->orderBy('start_date')->paginate($perPage);
    return response()->json($contracts);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  public function myContracts(Request $request)
  {
    $user = Auth::user();
    $user_id = $user->id;

    $query = Contract::with(['occupant', 'niche', 'representative', 'state'])
      ->where('representative_user_id', $user_id);

    return response()->json($query->get());
  }
  /**
   * Get the states of the contract
   */
  public function states()
  {
    $states = ContractState::all();
    return response()->json($states);
  }

  /**
   * Display the specified resource.
   */
  public function show($contract_id)
  {
    $contract = Contract::with(['occupant.gender', 'niche', 'representative', 'state', 'payments', 'currentPayment'])->find($contract_id);
    if (!$contract) {
      return response()->json(['message' => 'Contract not found'], 404);
    }
    return response()->json($contract);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $contract_id)
  {
    // Get the contract
    $contract = Contract::find($contract_id);
    if (!$contract) {
      return response()->json(['message' => 'Contrato no encontrado'], 404);
    }

    // Get the action to perform from the request
    $action = $request->input('action');
    if ($action == 'approve') {
      $contractState = ContractState::where('slug', 'aprobado')->first();
      $contract->state_id = $contractState->id;
    } elseif ($action == 'reject') {
      $contractState = ContractState::where('slug', 'rechazado')->first();
      $contract->state_id = $contractState->id;
      // Update the niche state to available
      $niche = $contract->niche;
      if ($niche) {
        $niche->state_id = NicheState::where('slug', 'disponible')->first()->id;
        $niche->save();
      }
    } else {
      return response()->json(['message' => 'Acción no válida'], 400);
    }
    // Save the contract
    $contract->save();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Contract $contract)
  {
    //
  }
}
