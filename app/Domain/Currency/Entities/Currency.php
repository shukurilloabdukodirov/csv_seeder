<?php

namespace App\Domain\Currency\Entities;

use App\Models\Base;

class Currency extends Base
{
    protected $fillable = [
        'name',
        'nominal',
        'value',
        'rate',
        'cbr_id'
    ];
}
