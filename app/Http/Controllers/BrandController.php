<?php

namespace App\Http\Controllers;

use App\Services\BrandService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
  protected $brandService;
  
  public function __construct(BrandService $brandService)
  {
    $this->brandService = $brandService;
  }
  
  public function store(Request $request)
  {
    try {
      // Definir as regras de validação
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:brands,name',
      ]);

      // Se a validação falhar, retornar a resposta com os erros
      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
      }

      // Chamar o Service para criar a marca
      $brand = $this->brandService->createBrand($request->all());
      
      // Retornar a resposta de sucesso
      return response()->json($brand, 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao criar a marca.', 'error' => $e->getMessage()], 500);
    }
  }
}