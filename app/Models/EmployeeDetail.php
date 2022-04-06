<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EmployeeDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id' , 'emp_code', 'designation', 'date_of_join', 'basic_pay',
        'hra','conveyance','gratuity_pay','special_allowance','variable_incentive','city_id','address','latitude','longitude'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
