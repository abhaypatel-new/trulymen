<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'email',
        'street',
        'city',
        'country',
        'area',
        'plot',
        'phone',
        'pin',
        'state',
        'attention',
       
    ];


   
}
