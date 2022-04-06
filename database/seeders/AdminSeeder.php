<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
use App\Models\City;
use App\Models\Task;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminDetails = array(
            [
                'name' => 'SuperAdmin',
                'mobile' => '9943308193',
                'email' => 'admin@admin.com',
                'password' => 'password',
               
            ],
        );

        $roles = [
                    [
                        'name' => 'administrator',
                        'description' => 'Super User, having access to all sites.'
                    ],
                    [
                        'name' => 'manager',
                        'description' => 'Manager, having limited access.'
                    ],
                    [
                        'name' => 'employee',
                        'description' => 'Employee has access of web app.'
                    ]
                ];

        foreach($roles as $key => $value){
            Role::create($value);
        }
        $role = Role::where('name', '=', 'administrator')->first();

        foreach ($adminDetails as $adminDetail) {
            $admin = User::where('mobile', '=', $adminDetail['mobile'])->first();

            if (!$admin) {
                $admin = User::create([
                    'name' => $adminDetail['name'],
                    'mobile' => $adminDetail['mobile'],
                    'email' => $adminDetail['email'],
                    'password' => Hash::make($adminDetail['password'])                  
                ]);

                UserRole::create([
                    'user_id' => $admin->id,
                    'role_id' => $role->id
                ]);
            }
        }

        $cities = [
               ['name' => 'Bangalore'],
               ['name' => 'Chennai']
        ];
        foreach($cities as $key => $city){
           City::create($city);
        }

        $tasks = [
            [
                 'name' => 'System Issues',
                 'description' => "System Not working"
            ],
            [
                'name' => 'Network Issues',
                'description' => "Internet Not working"
            ]
        ];
        foreach($tasks as $key => $task){
          Task::create($task);
        }

       
    }
}
