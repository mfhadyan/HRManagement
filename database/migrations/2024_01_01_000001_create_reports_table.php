<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('transaction_id')->unique();
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->string('payment_method');
            $table->unsignedBigInteger('cashier_id');
            $table->time('transaction_time');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('cashier_id')->references('id')->on('employees')->onDelete('cascade');
            $table->index(['transaction_date', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
} 