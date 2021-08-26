<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('home_conditions')->insert([
            [
                'slug' => 'new-homes',
                'title' => 'New Homes',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'slug' => 'good-condition',
                'title' => 'Good Condition',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'slug' => 'needs-renovating',
                'title' => 'Needs Renovating',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]);
    }
}
