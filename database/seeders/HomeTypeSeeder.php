<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('home_types')->insert([
            [
                'slug' => 'flat-apartments',
                'title' => 'flat / apartments',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'slug' => 'houses',
                'title' => 'Houses',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ], [
                'slug' => 'country-homes',
                'title' => 'Country homes',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ],[
                'slug' => 'duplex',
                'title' => 'Duplex',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ],[
                'slug' => 'penthouses',
                'title' => 'Penthouses',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        ]);
    }
}
