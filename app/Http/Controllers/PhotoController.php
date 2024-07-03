<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Services\PhotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
  protected $photoService;
  
  public function __construct(PhotoService $photoService)
  {
    $this->photoService = $photoService;
  }

  public function store(Request $request)
  {
    try {
      // Definir as regras de validação
      $validator = Validator::make($request->all(), [
        'path' => 'required|string|max:255',
        'car_id' => 'required|exists:cars,id',
      ]);
      
      // Se a validação falhar, retornar a resposta com os erros
      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
      }
      
      // Criar uma nova foto
      $photo = $this->photoService->createPhoto($request->all());
      
      // Retornar a resposta de sucesso
      return response()->json($photo, 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao adicionar imagem do carro.', 'error' => $e->getMessage()], 500);
    }
  }
}