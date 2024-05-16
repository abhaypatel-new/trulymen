<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    protected $table = 'jobcards';
    protected $fillable = [
       
        'deal_id',
        'so_number',
        'created_by',
        
    ];

    

   

    // public function labels()
    // {
    //     if($this->labels)
    //     {
    //         return Label::whereIn('id', explode(',', $this->labels))->get();
    //     }

    //     return false;
    // }

    public function deals()
    {
        return $this->hasOne('App\Models\deal', 'id', 'deal_id');
    }

    // public function stage()
    // {
    //     return $this->hasOne('App\Models\Stage', 'id', 'stage_id');
    // }

    // public function group()
    // {
    //     return $this->hasOne('App\Models\Group', 'id', 'group_id');
    // }

    // public function clients()
    // {
    //     return $this->belongsToMany('App\Models\User', 'client_deals', 'deal_id', 'client_id');
    // }

    // public function users()
    // {
    //     return $this->belongsToMany('App\Models\User', 'user_deals', 'deal_id', 'user_id');
    // }

    // public function products()
    // {
    //     if($this->products)
    //     {
    //         return ProductService::whereIn('id', explode(',', $this->products))->get();
    //     }

    //     return [];
    // }

    // public function sources()
    // {
    //     if($this->sources)
    //     {
    //         return Source::whereIn('id', explode(',', $this->sources))->get();
    //     }

    //     return [];
    // }

    // public function files()
    // {
    //     return $this->hasMany('App\Models\DealFile', 'deal_id', 'id');
    // }

    // public function tasks()
    // {
    //     return $this->hasMany('App\Models\DealTask', 'deal_id', 'id');
    // }

    // public function complete_tasks()
    // {
    //     return $this->hasMany('App\Models\DealTask', 'deal_id', 'id')->where('status', '=', 1);
    // }

    // public function invoices()
    // {
    //     return $this->hasMany('App\Models\Invoice', 'deal_id', 'id');
    // }

    // public function calls()
    // {
    //     return $this->hasMany('App\Models\DealCall', 'deal_id', 'id');
    // }

    // public function emails()
    // {
    //     return $this->hasMany('App\Models\DealEmail', 'deal_id', 'id')->orderByDesc('id');
    // }

    // public function activities()
    // {
    //     return $this->hasMany('App\Models\ActivityLog', 'deal_id', 'id')->orderBy('id', 'desc');
    // }

    // public function discussions()
    // {
    //     return $this->hasMany('App\Models\DealDiscussion', 'deal_id', 'id')->orderBy('id', 'desc');
    // }

    // public static function getDealSummary($deals)
    // {
    //     $total = 0;

    //     foreach($deals as $deal)
    //     {
    //         $total += $deal->price;
    //     }

    //     return \Auth::user()->priceFormat($total);
    // }
}
