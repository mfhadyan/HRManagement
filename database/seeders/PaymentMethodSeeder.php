<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            ['payment_method_name' => 'Cash'],
            ['payment_method_name' => 'Credit Card'],
            ['payment_method_name' => 'Debit Card'],
            ['payment_method_name' => 'E-Wallet'],
            ['payment_method_name' => 'Bank Transfer'],
        ];

        foreach ($paymentMethods as $paymentMethod) {
            PaymentMethod::create($paymentMethod);
        }
    }
} 