<?php

namespace App\Services;

use App\Models\Car;

class CarService
{
  public function listOrFilter(array $data)
  {
    $query = Car::query();
    
    if (isset($data['license_plate'])) {
      $query->where('license_plate', 'like', '%' . $data['license_plate'] . '%');
    }
    
    if (isset($data['model_id'])) {
      $query->where('model_id', $data['model_id']);
    }
    
    if (isset($data['brand_id'])) {
      $query->whereHas('model', function ($query) use ($data) {
        $query->where('brand_id', $data['brand_id']);
      });
    }
    
    if (isset($data['year'])) {
      $query->where('year', $data['year']);
    }
    
    if (isset($data['color'])) {
      $query->where('color', 'like', '%' . $data['color'] . '%');
    }
    
    if (isset($data['price_min'])) {
      $query->where('price_per_day', '>=', $data['price_min']);
    }
    
    if (isset($data['price_max'])) {
      $query->where('price_per_day', '<=', $data['price_max']);
    }
    
    if (isset($data['is_available'])) {
      $query->where('is_available', $data['is_available']);
    }

    return $query->with(['rentals.user'])->get();
  }

  public function createCar(array $data)
  {
    return Car::create([
      'license_plate' => $data['license_plate'],
      'model_id' => $data['model_id'],
      'year' => $data['year'],
      'color' => $data['color'],
      'price_per_day' => $data['price_per_day']
    ]);
  }
}