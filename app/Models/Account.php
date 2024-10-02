<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'accounts';

    // Define which fields are mass assignable
    protected $fillable = [
        'name',
        'company',
        'external_reference',
    ];

    // Define any relationships here (e.g., if Account has many Products)
}

