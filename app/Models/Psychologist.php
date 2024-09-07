<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Psychologist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "email",
        "phone_number",
        "address",
        "latitude",
        "longitude"
    ];

    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => [
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude']
            ],
            set: fn(array $value) => [
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude']
            ],
        );
    }

}
