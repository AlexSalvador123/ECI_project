<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    // Define which fields are mass assignable
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
    ];

    // Define any relationships here (e.g., if Product belongs to Account)
}

