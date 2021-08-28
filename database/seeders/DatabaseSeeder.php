<?php

namespace Database\Seeders;

use App\Models\HomeCondition;
use App\Models\HomeType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new UserTypeSeeder())->run();
        (new HomeConditionSeeder())->run();
        (new HomeTypeSeeder())->run();
        (new UserSeeder())->run();
    }
}
