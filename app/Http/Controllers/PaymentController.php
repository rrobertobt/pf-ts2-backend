<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'contract_id' => 'required|exists:contracts,id',
    ]);

    try {
      DB::beginTransaction();

      // Check if the contract has state of 'aprobado', search by slug
      $contract = Contract::find($request->contract_id);
      if ($contract->state->slug !== 'aprobado') {
        return response()->json(['message' => 'Contrato no habilitado para pagos'], 422);
      }

      // Get the amount from the contract
      $amount = $contract->price;
      $request->merge(['amount' => $amount]);
      // Check if the contract has a current payment
      if ($contract->current_payment_id) {
        return response()->json(['message' => 'El contrato ya tiene un pago activo'], 422);
      }

      // Generate a unique correlative number (simulate a real-world scenario like CM-123456)
      $lastPayment = Payment::orderBy('id', 'desc')->first();
      $correlative = 'CM-' . str_pad(($lastPayment ? $lastPayment->id + 1 : 1), 6, '0', STR_PAD_LEFT);
      $request->merge(['correlative' => $correlative]);

      // Create the payment
      $payment = Payment::create($request->all());

      // Check if the contract has a current_payment, if not, set it to the new payment
      if (!$contract->current_payment_id) {
        $contract->current_payment_id = $payment->id;
        $contract->save();
      }

      return response()->json($payment, 201);

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['message' => 'Error al crear el pago'], 422);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Payment $payment)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Payment $payment)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Payment $payment)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Payment $payment)
  {
    //
  }
}
