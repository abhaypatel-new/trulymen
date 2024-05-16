<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecificationCodeMaterial extends Model
{
    protected $fillable = [
        'name',
        'specification_code_order_id',
        'unit_rate',
        'image',
        'qty',
    ];

    public static $colors = [
        'primary',
        'secondary',
        'danger',
        'warning',
        'info',
        'success',
    ];

  
}
