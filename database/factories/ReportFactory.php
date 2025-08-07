<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paymentMethods = ['Cash', 'Credit Card', 'Debit Card', 'E-Wallet', 'Bank Transfer'];
        $products = [
            'Coffee Latte', 'Espresso', 'Cappuccino', 'Americano', 'Mocha',
            'Tea', 'Green Tea', 'Black Coffee', 'Hot Chocolate', 'Iced Coffee',
            'Sandwich', 'Burger', 'Pizza Slice', 'Cake', 'Cookie',
            'French Fries', 'Chicken Wings', 'Salad', 'Soup', 'Pasta'
        ];

        $quantity = $this->faker->numberBetween(1, 10);
        $unitPrice = $this->faker->randomFloat(2, 2.00, 25.00);
        $totalSales = $quantity * $unitPrice;

        // Generate a simple unique transaction ID
        $date = $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d');
        $dateFormatted = str_replace('-', '', $date);
        $randomNumber = $this->faker->unique()->numberBetween(1, 9999);
        $transactionId = $dateFormatted . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);

        return [
            'transaction_date' => $date,
            'transaction_id' => $transactionId,
            'product_name' => $this->faker->randomElement($products),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_sales' => $totalSales,
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'cashier_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory(),
            'transaction_time' => $this->faker->time('H:i:s'),
            'comments' => $this->faker->optional(0.3)->sentence(),
        ];
    }
} 