<?php

namespace App\Http\Controllers;

use App\Models\Niche;
use App\Models\NicheState;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
  public function payments()
  {
    $payments_count = Payment::where('paid', true)->get()->count();
    $pending_payments_count = Payment::where('paid', false)->get()->count();
    $payments = Payment::where('paid', true)->get()->sum('amount');

    $payments = [
      'total_count' => $payments_count + $pending_payments_count,
      'paid_count' => $payments_count,
      'pending_count' => $pending_payments_count,
      'total_money' => $payments
    ];
    return response()->json([
      'data' => $payments
    ]);
  }

  public function niches()
  {
    // get the id of the slug state 'disponible'
    $occupied_state = NicheState::where('slug', 'ocupado')->first();
    $available_state = NicheState::where('slug', 'disponible')->first();
    $exhumation_state = NicheState::where('slug', 'exhumacion')->first();

    $niches_count = Niche::where('state_id', $occupied_state->id)->get()->count();
    $available_niches_count = Niche::where('state_id', $available_state->id)->get()->count();
    $exhumation_niches_count = Niche::where('state_id', $exhumation_state->id)->get()->count();
    $niches = [
      'total_count' => $niches_count + $available_niches_count + $exhumation_niches_count,
      'occupied_count' => $niches_count,
      'available_count' => $available_niches_count,
      'exhumation_count' => $exhumation_niches_count
    ];
    return response()->json([
      'data' => $niches
    ]);
    // $niches = DB::table('niches')
    //   ->join('niches_states', 'niches.state_id', '=', 'niches_states.id')
    //   ->select('niches_states.slug', 'niches_states.name', DB::raw('count(*) as total'))
    //   ->groupBy('niches_states.slug', 'niches_states.name')
    //   ->get();

    // // Calcular el total sumando todos los conteos
    // $total_count = $niches->sum('total');

    // return response()->json([
    //   'data' => [
    //     'total_count' => $total_count,
    //     'details' => $niches,
    //   ]
    // ]);
  }

  public function occupants()
  {
    $genderStats = DB::table('occupants')
      ->join('genders', 'occupants.gender_id', '=', 'genders.id')
      ->select('genders.slug', 'genders.name', DB::raw('count(*) as total'))
      ->groupBy('genders.slug', 'genders.name')
      ->get();

    $ageStats = DB::table('occupants')
      ->selectRaw("
          CASE
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 0 AND 10 THEN '0-10'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 11 AND 20 THEN '11-20'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 21 AND 30 THEN '21-30'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 31 AND 40 THEN '31-40'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 41 AND 50 THEN '41-50'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 51 AND 60 THEN '51-60'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 61 AND 70 THEN '61-70'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 71 AND 80 THEN '71-80'
              WHEN EXTRACT(YEAR FROM age(death_date, date_of_birth)) BETWEEN 81 AND 90 THEN '81-90'
              ELSE '91+'
          END as rango_edad,
          COUNT(*) as total
      ")
      ->groupBy('rango_edad')
      ->orderBy('rango_edad')
      ->get();

    return response()->json([
      'data' => [
        'gender' => $genderStats,
        'age' => $ageStats
      ]
    ]);
  }
}
