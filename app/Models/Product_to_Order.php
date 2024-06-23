<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_to_Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'product_to_order';
}
