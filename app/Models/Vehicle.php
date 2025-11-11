<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_type_id',
        'location_id',
        'make',
        'model',
        'registration_number',
        'year',
        'price_per_day',
        'status',
        'image'
    ];

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // app/Models/Vehicle.php

    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('images/default-car.jpg'); // fallback image in /public/images
    }

}
