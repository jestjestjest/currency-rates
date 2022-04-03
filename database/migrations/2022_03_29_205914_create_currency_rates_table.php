<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 3)->nullable();
            $table->unsignedBigInteger('base_currency_id')->nullable();
            $table->date('rate_date');
            $table->float('rate_value', 8, 4);
            $table->timestamps();

            $table->foreign('base_currency_id')->references('id')->on('base_currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_rates');
    }
};
