<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::get();

        return response()->json($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        $brand = Brand::create([
            'brand_name' => $request->brand_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully!',
            'brand' => $brand,
        ], 201);
    }
}
