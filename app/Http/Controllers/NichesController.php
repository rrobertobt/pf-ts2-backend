<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Niche;
use App\Models\NicheState;
use App\Models\NichesType;
use Illuminate\Http\Request;

class NichesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $niches = Niche::with('state')->get();
        $query = Niche::with('state', 'type');

        if (request()->has('avenue_location')) {
            $query->where('avenue_location', 'ilike', '%' . request()->query('avenue_location') . '%');
        }
        if (request()->has('street_location')) {
            $query->where('street_location', 'ilike', '%' . request()->query('street_location') . '%');
        }
        if (request()->has('code')) {
            $query->where('code', 'ilike', '%' . request()->query('code') . '%');
        }
        if (request()->has('state_id')) {
            $query->where('state_id', request()->query('state_id'));
        }
        if (request()->has('type_id')) {
            $query->where('type_id', request()->query('type_id'));
        }
        if (request()->has('is_historical')) {
            $query->where('is_historical', request()->query('is_historical'));
        }
        // available flag
        if (request()->has('available')) {
          error_log('available');
            $query->whereHas('state', function ($q) {
                $q->where('slug', 'disponible');
            });
        }

        $perPage = request()->query('per_page', 10);

        $niches = $query->orderBy('id')->paginate($perPage);
        return response()->json($niches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        $request->validate([
            'avenue_location' => 'required|string|max:255',
            'street_location' => 'required|string|max:255',
            'type_id' => 'required|exists:niches_types,id',
            'is_historical' => 'boolean',
            'code' => 'required|string|max:255|unique:niches,code'
        ]);

        // make the default state_id of slug 'disponible'
        $state = NicheState::where('slug', 'disponible')->first();

        $request->merge(['state_id' => $state->id]);
        $niche = Niche::create($request->all());
        return response()->json($niche, 201);
    }

    public function states()
    {
      
        $nicheStates = NicheState::all();
        return response()->json($nicheStates);
    }
    public function types()
    {
        $nicheTypes = NichesType::all();
        return response()->json($nicheTypes);
    }
}
