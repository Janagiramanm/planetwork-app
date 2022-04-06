<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserRole;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'        
    ];

    public function users() {
        return $this->belongsToMany(User::class)->using(UserRole::class);
    }

    public function user_role(){
        return $this->hasMany(UserRole::class);
    }

}
