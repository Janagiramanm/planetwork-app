<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Api\Leave;

class LeaveDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'status','reject_reason'               
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function leave(){
        return $this->belongsTo(Leave::class,'user_id','user_id');
    }
}
