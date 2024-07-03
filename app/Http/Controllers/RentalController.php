<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Car;
use App\Services\RentalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
  protected $rentalService;
  
  public function __construct(RentalService $rentalService)
  {
    $this->rentalService = $rentalService;
  }

  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'car_id' => 'required|exists:cars,id',
        'user_id' => 'required|exists:users,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
      ]);
      
      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
      }
      
      // Verificar se o carro já está alugado no período especificado
      $car = Car::find($request->car_id);
      $isRented = $this->rentalService->verifyRent($request->all(), $car);
      
      if ($isRented) {
        return response()->json(['error' => 'Car is already rented for the selected dates'], 422);
      }
      
      $rental = $this->rentalService->createRental($request->all());
      
      // Atualizar a disponibilidade do carro
      $car->is_available = false;
      $car->save();
      
      return response()->json($rental, 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao alugar carro.', 'error' => $e->getMessage()], 500);
    }
  }
}