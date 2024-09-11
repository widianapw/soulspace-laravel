<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Psychologist extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $appends = ["image_url"];
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

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl("image");
    }

}
