<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'department_id','name','created_by'
    ];
    
    protected $casts = [
    'created_at'  => 'datetime:F j, Y, g:i a',
    'updated_at' => 'datetime:F j, Y, g:i a',
];
}
