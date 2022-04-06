<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\CustomerLocation;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name' , 'last_name', 'customer_type', 'company_name','phone', 'customer_email', 'email', 'website'
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function customerLocation(){
        return $this->hasMany(CustomerLocation::class);
    }
}
