<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * The Product class is responsible for collecting product details.
 */
class Product extends Base
{
    public $timestamps = false;
    protected $table = "products";
    protected $fillable = [
        'strProductName',
        'strProductDesc',
        'strProductCode',
        'stock',
        'price',
        'dtmAdded',
        'dtmDiscontinued',
    ];
}
