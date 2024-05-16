<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    
   
    protected $fillable = [
        'name',
        'price',
        'priority',
        'sku',
        'image',
        'created_by',
    ];

    public $customField;
    
    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
    public function subspecifications()
    {
        return $this->hasMany('App\Models\Specification', 'priority');
    }
}
