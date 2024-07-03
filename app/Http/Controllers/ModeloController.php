<?php

namespace App\Http\Controllers;

use App\Services\ModeloService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModeloController extends Controller
{
  protected $modeloService;
  
  public function __construct(ModeloService $modeloService)
  {
    $this->modeloService = $modeloService;
  }

  public function store(Request $request)
  {
    try {
      // Definir as regras de validação
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'brand_id' => 'required|exists:brands,id',
      ]);
      
      // Se a validação falhar, retornar a resposta com os erros
      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
      }
      
      // Criar um novo modelo
      $model = $this->modeloService->createModelo($request->all());
      
      // Retornar a resposta de sucesso
      return response()->json($model, 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao criar o modelo.', 'error' => $e->getMessage()], 500);
    }
  }
}