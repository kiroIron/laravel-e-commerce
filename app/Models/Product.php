<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Allow mass assignment on language-specific and other fields.
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'images',
        'price',
        'discounted_price',
        'quantity',
        'status',
        'category_id'
    ];

    // Cast fields appropriately.
    protected $casts = [
        'images' => 'array',
        'status' => 'boolean',
        'price' => 'float',
        'discounted_price' => 'float',
    ];

    // Hide the raw language-specific fields from JSON.
    protected $hidden = ['name_en', 'name_ar', 'description_en', 'description_ar'];

    // Append computed attributes.
    protected $appends = ['name', 'description'];

    // Accessor: returns localized name.
    public function getNameAttribute()
    {
        $locale = app()->getLocale(); // 'en' or 'ar'
        return $this->{"name_{$locale}"} ?? $this->name_en;
    }

    // Accessor: returns localized description.
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_en;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
