<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('note')->unique();
            $table->tinyInteger('weight');
            $table->date('create_date');
            $table->date('finish_date');
            $table->integer('tax')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('total');
            $table->boolean('payment_status')->default(0);
            $table->boolean('working_status')->default(0);
            $table->foreignId('packet_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
