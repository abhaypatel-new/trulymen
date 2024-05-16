<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecificationCodeOrder extends Model
{
    protected $fillable = [
        'name',
       
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
