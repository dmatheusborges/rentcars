<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Rental;

class RentalService
{
  public function verifyRent(Array $data, Car $car)
  {
    return Rental::where('car_id', $car->id)
    ->where(function($query) use ($data) {
      $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
      ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
    })->exists();
  }

  public function createRental(array $data)
  {
    return Rental::create([
      'car_id' => $data['car_id'],
      'user_id' => $data['user_id'],
      'start_date' => $data['start_date'],
      'end_date' => $data['end_date'],
    ]);
  }
}