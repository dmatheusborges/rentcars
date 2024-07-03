<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Services\CarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
  protected $carService;
  
  public function __construct(CarService $carService)
  {
    $this->carService = $carService;
  }
  
  /**
  * List all available cars or filter by parameters.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    try {
      // Incluir informações de aluguel se o carro não estiver disponível
      $cars = $this->carService->listOrFilter($request->all());
      
      // Formatando a resposta para incluir informações de aluguel se não estiver disponível
      $formattedCars = $cars->map(function ($car) {
        $formattedCar = $car->toArray();
        
        if (!$car->is_available) {
          $rental = $car->rentals->last(); // Obtém o último aluguel (assumindo o mais recente)
          $formattedCar['rented_by'] = [
            'user_id' => $rental->user_id,
            'user_name' => $rental->user->name,
            'start_date' => $rental->start_date,
            'end_date' => $rental->end_date,
          ];
        }
        
        // Formatando as datas created_at e updated_at
        $formattedCar['created_at'] = $car->created_at->format('d/m/Y H:i:s');
        $formattedCar['updated_at'] = $car->updated_at->format('d/m/Y H:i:s');
        
        return $formattedCar;
      });
      
      // Retornar os resultados
      return response()->json($formattedCars);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao encontrar carros.', 'error' => $e->getMessage()], 500);
    }
  }
  
  public function store(Request $request)
  {
    try {
      // Definir as regras de validação
      $validator = Validator::make($request->all(), [
        'license_plate' => 'required|string|unique:cars,license_plate|max:10',
        'model_id' => 'required|exists:models,id',
        'year' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
        'color' => 'required|string|max:20',
        'price_per_day' => 'required|numeric|min:0'
      ]);
      
      // Se a validação falhar, retornar a resposta com os erros
      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
      }
      
      // Criar um novo carro
      $car = $this->carService->createCar($request->all());
      
      // Retornar a resposta de sucesso
      return response()->json($car, 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao criar um carro.', 'error' => $e->getMessage()], 500);
    }
  }
}