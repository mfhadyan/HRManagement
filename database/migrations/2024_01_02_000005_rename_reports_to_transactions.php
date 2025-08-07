<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameReportsToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, create the new tables if they don't exist
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id('payment_method_id');
                $table->string('payment_method_name', 20);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id('product_id');
                $table->string('product_name', 100)->unique();
                $table->decimal('unit_price', 10, 2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id('transaction_id');
                $table->date('date');
                $table->time('transaction_time');
                $table->unsignedBigInteger('cashier_id');
                $table->unsignedBigInteger('payment_method_id');
                $table->text('comments')->nullable();
                $table->timestamps();

                $table->foreign('cashier_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods')->onDelete('cascade');
                $table->index(['date', 'transaction_id']);
            });
        }

        if (!Schema::hasTable('transaction_details')) {
            Schema::create('transaction_details', function (Blueprint $table) {
                $table->id('transaction_detail_id');
                $table->unsignedBigInteger('transaction_id');
                $table->unsignedBigInteger('product_id');
                $table->integer('quantity');
                $table->decimal('historical_unit_price', 10, 2);
                $table->timestamps();

                $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->onDelete('cascade');
                $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            });
        }

        // Seed payment methods if table is empty
        if (DB::table('payment_methods')->count() == 0) {
            $paymentMethods = [
                ['payment_method_name' => 'Cash'],
                ['payment_method_name' => 'Credit Card'],
                ['payment_method_name' => 'Debit Card'],
                ['payment_method_name' => 'E-Wallet'],
                ['payment_method_name' => 'Bank Transfer'],
            ];

            foreach ($paymentMethods as $paymentMethod) {
                DB::table('payment_methods')->insert($paymentMethod);
            }
        }

        // Migrate existing reports data to new structure
        if (Schema::hasTable('reports')) {
            $reports = DB::table('reports')->get();
            
            foreach ($reports as $report) {
                // Create or find payment method
                $paymentMethod = DB::table('payment_methods')
                    ->where('payment_method_name', $report->payment_method)
                    ->first();
                
                if (!$paymentMethod) {
                    $paymentMethodId = DB::table('payment_methods')->insertGetId([
                        'payment_method_name' => $report->payment_method,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $paymentMethodId = $paymentMethod->payment_method_id;
                }

                // Create or find product
                $product = DB::table('products')
                    ->where('product_name', $report->product_name)
                    ->first();
                
                if (!$product) {
                    $productId = DB::table('products')->insertGetId([
                        'product_name' => $report->product_name,
                        'unit_price' => $report->unit_price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $productId = $product->product_id;
                }

                // Create transaction
                $transactionId = DB::table('transactions')->insertGetId([
                    'date' => $report->transaction_date,
                    'transaction_time' => $report->transaction_time,
                    'cashier_id' => $report->cashier_id,
                    'payment_method_id' => $paymentMethodId,
                    'comments' => $report->comments,
                    'created_at' => $report->created_at,
                    'updated_at' => $report->updated_at,
                ]);

                // Create transaction detail
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'product_id' => $productId,
                    'quantity' => $report->quantity,
                    'historical_unit_price' => $report->unit_price,
                    'created_at' => $report->created_at,
                    'updated_at' => $report->updated_at,
                ]);
            }

            // Drop the old reports table
            Schema::dropIfExists('reports');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration is complex to reverse, so we'll just drop the new tables
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('payment_methods');
    }
} 