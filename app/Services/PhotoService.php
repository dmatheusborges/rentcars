<?php

namespace App\Services;

use App\Models\Photo;

class PhotoService
{
  public function createPhoto(array $data)
  {
    return Photo::create([
      'path' => $data['path'],
      'car_id' => $data['car_id'],
    ]);
  }
}