<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerLocation;


class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'               
    ];

    public function customerLocation(){
        return $this->hasMany(CustomerLocation::class,'id','city_id');
    }

    
}
