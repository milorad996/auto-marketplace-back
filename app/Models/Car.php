<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{


    protected $fillable = [
        'user_id',
        'model_id',
        'brand_id',
        'price',
        'manufacture_year',
        'mileage',
        'body_type',
        'fuel_type',
        'door_count',
        'description'
    ];

    use HasFactory;

    public function model()
    {
        return $this->belongsTo(CarModel::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
