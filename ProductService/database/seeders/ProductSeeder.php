<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('products')->insert([
                'name' => "Product $i",
                'description' => "Description for Product $i",
                'price' => rand(10, 100),
                'quantity' => rand(1, 50),
                // Add more columns as needed
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
