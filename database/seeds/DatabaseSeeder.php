<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Modele;
use App\Models\Country;
use App\Models\City;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(DoctorSeeder::class);
        $this->call(LaratrustSeeder::class);
 
        
    }
}
