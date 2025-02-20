<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 16);

        $cars = Car::with(['images', 'model.brand:id,brand_name'])
            ->orderBy('created_at', 'desc')
            ->paginate($per_page);

        $cars->getCollection()->transform(function ($car) {
            return [
                'id' => $car->id,
                'brand' => $car->model->brand->brand_name,
                'model' => $car->model->model_name,
                'price' => $car->price,
                'manufacture_year' => $car->manufacture_year,
                'mileage' => $car->mileage,
                'body_type' => $car->body_type,
                'fuel_type' => $car->fuel_type,
                'door_count' => $car->door_count,
                'description' => $car->description,
                'images' => $car->images->pluck('url'),
            ];
        });

        return response()->json($cars);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CarRequest $request)
    {


        DB::beginTransaction();

        try {
            $carModel = CarModel::firstOrCreate(
                [
                    'model_name' => $request->model_name,
                    'brand_id' => $request->brand_id,
                ]
            );

            $car = Car::create([
                'user_id' => $request->user()->id,
                'model_id' => $carModel->id,
                'brand_id' => $request->brand_id,
                'price' => $request->price,
                'manufacture_year' => $request->manufacture_year,
                'mileage' => $request->mileage,
                'body_type' => $request->body_type,
                'fuel_type' => $request->fuel_type,
                'door_count' => $request->door_count,
                'description' => $request->description,
            ]);

            if ($request->has('images')) {
                foreach ($request->images as $imageUrl) {
                    Image::create([
                        'url' => $imageUrl,
                        'car_id' => $car->id,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Car and model created successfully!', 'car' => $car], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while creating the car.', 'details' => $e->getMessage()], 500);
        }
    }



    public function filter(Request $request)
    {
        $query = Car::query()
            ->select('cars.*', 'car_models.model_name', 'brands.brand_name')
            ->join('car_models', 'cars.model_id', '=', 'car_models.id')
            ->join('brands', 'car_models.brand_id', '=', 'brands.id');

        $query->with('images');

        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('car_models.brand_id', $request->brand_id);
        }

        if ($request->has('model_name') && $request->model_name != '') {
            $query->where('car_models.model_name', $request->model_name);
        }

        if ($request->has('price') && $request->price != '') {
            $query->where('cars.price', '<=', $request->price);
        }

        if ($request->has('year_from') && $request->year_from != '') {
            $query->where('cars.manufacture_year', '>=', $request->year_from);
        }

        if ($request->has('year_to') && $request->year_to != '') {
            $query->where('cars.manufacture_year', '<=', $request->year_to);
        }
        if ($request->has('fuel_type') && $request->fuel_type != '') {
            $query->where('cars.fuel_type', $request->fuel_type);
        }

        if ($request->has('door_count') && $request->door_count != '') {
            $query->where('cars.door_count', $request->door_count);
        }

        return response()->json($query->get());
    }




    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        $car->load(['images', 'model.brand:id,brand_name']);

        $carData = [
            'id' => $car->id,
            'user_id' => $car->user_id,
            'brand' => $car->model->brand->brand_name,
            'model' => $car->model->model_name,
            'price' => $car->price,
            'manufacture_year' => $car->manufacture_year,
            'mileage' => $car->mileage,
            'body_type' => $car->body_type,
            'fuel_type' => $car->fuel_type,
            'door_count' => $car->door_count,
            'description' => $car->description,
            'images' => $car->images->pluck('url'),
        ];

        return response()->json($carData);
    }

    public function getSimilarCars($brand, $carId)
    {
        $cars = Car::query()
            ->select('cars.*', 'car_models.model_name', 'brands.brand_name')
            ->join('car_models', 'cars.model_id', '=', 'car_models.id')
            ->join('brands', 'car_models.brand_id', '=', 'brands.id')
            ->where('brands.brand_name', $brand)
            ->where('cars.id', '!=', $carId)
            ->limit(8)
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'brand' => $car->brand_name,
                    'model' => $car->model_name,
                    'price' => $car->price,
                    'manufacture_year' => $car->manufacture_year,
                    'mileage' => $car->mileage,
                    'body_type' => $car->body_type,
                    'fuel_type' => $car->fuel_type,
                    'door_count' => $car->door_count,
                    'description' => $car->description,
                    'images' => $car->images->pluck('url'),
                ];
            });

        return response()->json($cars);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        if (auth()->user()->id !== $car->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            $car->images()->delete();
            $car->delete();
            DB::commit();
            return response()->json(['message' => 'Car deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete car', 'details' => $e->getMessage()], 500);
        }
    }
}
