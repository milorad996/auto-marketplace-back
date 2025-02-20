<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    public function getModelsByBrand($brandId)
    {
        $models = CarModel::where('brand_id', $brandId)->distinct('model_name')->get();

        return response()->json($models);
    }
}
