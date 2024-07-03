<?php

namespace App\Services;

use App\Models\Brand;

class BrandService
{
  public function createBrand(array $data)
  {
    return Brand::create([
      'name' => $data['name'],
    ]);
  }
}