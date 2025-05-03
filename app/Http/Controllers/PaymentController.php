<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractState;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\error;

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

      DB::commit();
      return response()->json($payment, 201);

    } catch (\Exception $e) {
      DB::rollBack();
      error_log($e->getMessage());
      return response()->json(['message' => 'Error al crear el pago'], 422);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($payment_id)
  {
    $payment = Payment::with(['contract.occupant', 'contract.niche', 'contract.representative'])->find($payment_id);
    if (!$payment) {
      return response()->json(['message' => 'Pago no encontrado'], 404);
    }
    return response()->json($payment);
  }
  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Payment $payment)
  {
    //
  }

  public function registerPay(Request $request, $payment_id) 
  {
    if (!$request->hasFile('file')) {
      return response()->json(['message' => 'No se ha subido ningún archivo'], 422);
    }
    $request->validate([
      'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);
    $payment = Payment::find($payment_id);
    if (!$payment) {
      return response()->json(['message' => 'Pago no encontrado'], 404);
    }
    if ($payment->paid) {
      return response()->json(['message' => 'El pago ya ha sido registrado'], 422);
    }
    if ($payment->evidence_url) {
      return response()->json(['message' => 'El pago ya tiene un archivo adjunto'], 422);
    }


    $file = $request->file('file');
    $path = $file->store('uploads', 'public');
    $finalPath = env('APP_URL') .':8000'. '/storage/' . $path;

    // Update the payment with the file path
    $payment->evidence_url = $finalPath;
    $payment->paid = true;
    $payment->payment_date = now();
    $payment->save();
    // Update the contract to the 'vigente' state
    $contract = Contract::find($payment->contract_id);
    // find the 'vigente' state
    $vigenteState = ContractState::where('slug', 'vigente')->first();
    if (!$vigenteState) {
      return response()->json(['message' => 'Estado vigente no encontrado'], 422);
    }
    $contract->state_id = $vigenteState->id;
    // Remove the current payment from the contract
    $contract->current_payment_id = null;
    $contract->save();

    return response()->json([
      'message' => 'Pago registrado correctamente',
      'path' => $path,
      'view' => env('APP_URL') .':8000'. '/storage/' . $path,
    ]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Payment $payment)
  {
    //
  }
}
