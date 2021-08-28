<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminType = DB::table('user_types')
            ->where('title','admin')
            ->first();
        DB::table('users')->insert([
            'name' => 'shaho',
            'family' => 'parivni',
            'phone' => '989189799672',
            'email' => 'shaho.parivni@gmail.com',
            'password' => bcrypt('Sh123456'),
            'address' => 'simple address',
            'type_id' => $adminType->id
        ]);
    }
}
