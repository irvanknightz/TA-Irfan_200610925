<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierDatabaseSeeder extends Seeder
{
    /**
     * Seed the supplier data into the database.
     *
     * @return void
     */
    public function run()
    {
        // Create a test supplier
        Supplier::factory()->create([
            'name' => 'Test Supplier',
        ]);

        // Create other suppliers using the factory
        Supplier::factory(4)->create();
    }
}
