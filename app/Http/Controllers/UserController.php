<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  protected $usersService;
  
  public function __construct(UsersService $usersService)
  {
    $this->usersService = $usersService;
  }

  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
      ]);
      
      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
      }
      
      // Chamar o Service para criar o usuário
      $user = $this->usersService->createUsers($request->all());
      
      return response()->json($user, 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao criar usuário.', 'error' => $e->getMessage()], 500);
    }
  }
  
  /**
  * Get rentals of a specific user.
  *
  * @param  int  $user_id
  * @return \Illuminate\Http\Response
  */
  public function getUserRentals($user_id)
  {
    try {
      $user = $this->usersService->getRentals($user_id);
      
      // Carregar os aluguéis do usuário
      $rentals = $user->rentals()->with('car')->get();
      
      return response()->json($rentals);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao encontrar aluguéis do usuário.', 'error' => $e->getMessage()], 500);
    }
  }
}