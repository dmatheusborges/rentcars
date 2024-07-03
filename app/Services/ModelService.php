<?php

namespace App\Services;

use App\Models\Modelo;

class ModeloService
{
  public function createModelo(array $data)
  {
    return Modelo::create([
      'name' => $data['name'],
      'brand_id' => $data['brand_id'],
    ]);
  }
}