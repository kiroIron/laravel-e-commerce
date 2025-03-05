<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Allow mass assignment on the language-specific fields.
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'image',
        'status',
    ];

    // Cast the status field to boolean.
    protected $casts = [
        'status' => 'boolean',
    ];

    // Hide the raw language-specific fields in JSON responses.
    protected $hidden = ['name_en', 'name_ar', 'description_en', 'description_ar'];

    // Append the computed attributes so they appear in the response.
    protected $appends = ['name', 'description'];

    // Accessor: returns the appropriate "name" based on the current locale.
    public function getNameAttribute()
    {
        $locale = app()->getLocale(); // 'en' or 'ar'
        return $this->{"name_{$locale}"} ?? $this->name_en;
    }

    // Accessor: returns the appropriate "description" based on the current locale.
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_en;
    }
}
