<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['product_name' => 'Coffee', 'unit_price' => 5.00],
            ['product_name' => 'Tea', 'unit_price' => 3.50],
            ['product_name' => 'Sandwich', 'unit_price' => 8.00],
            ['product_name' => 'Cake', 'unit_price' => 6.50],
            ['product_name' => 'Water', 'unit_price' => 2.00],
            ['product_name' => 'Juice', 'unit_price' => 4.00],
            ['product_name' => 'Pizza', 'unit_price' => 12.00],
            ['product_name' => 'Burger', 'unit_price' => 10.00],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 