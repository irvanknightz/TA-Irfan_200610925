<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplierPerformance;
use App\Models\Supplier; // Add the Supplier model

class SupplierPerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all suppliers
        $suppliers = Supplier::all();

        // Loop through each supplier and create supplier performance records
        foreach ($suppliers as $supplier) {
            // Create supplier performance record with a valid supplier ID
            SupplierPerformance::factory()->create([
                'supplier_id' => $supplier->id,
            ]);
        }
    }
}
