<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\User;
use App\Models\Task;
use App\Models\CustomerLocation;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_type' , 'customer_id', 'address', 'task_id','user_id', 'date'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function employees()
    {
        return $this->hasMany(AssignJobEmployee::class);
    }
    public function task(){
        return $this->belongsTo(Task::class);
    }

    public function customerLocation(){
        return $this->belongsTo(CustomerLocation::class,'address','id');
    }
}
