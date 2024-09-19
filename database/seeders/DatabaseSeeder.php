<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SupplierDatabaseSeeder;
use Database\Seeders\SupplierPerformanceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed suppliers first
        $this->call(SupplierDatabaseSeeder::class);

        // Then seed supplier performances
        $this->call(SupplierPerformanceSeeder::class);
    }
}

