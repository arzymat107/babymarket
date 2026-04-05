<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'image_url'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class);
    }

    public function ageGroups(): HasMany
    {
        return $this->hasMany(AgeGroup::class);
    }
}