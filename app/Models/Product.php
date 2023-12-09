<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

     protected $fillable = [
        'name',
        'quantity',
        'price',
        'detail',
        'image',

        // Add this line to allow mass assignment of _token
    ];

    use HasFactory;
}
