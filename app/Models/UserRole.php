<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','role_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role(){
        return $this->belongsTo(Role::class); 
    }

    public function roles() {
        $userRoles = UserRole::where('user_id', $this->id)->get()->pluck('role_id');
        return Role::whereIn('id', $userRoles);
    }

}
