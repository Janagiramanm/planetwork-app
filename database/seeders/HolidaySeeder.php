<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;


class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $holidays = [
            [
                'date' => '2021-01-26',
                'description' => 'Republic Day'
            ],
            [
                'date' => '2021-08-15',
                'description' => 'Independence Day'
            ]
        ];
        foreach($holidays as $key => $holiday){
            Holiday::create($holiday);
        }
    }
}
