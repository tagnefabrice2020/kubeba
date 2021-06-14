<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('shipement_id')->unsigned();
            $table->enum('type', ['document', 'electronic', 'clothing', 'others']);
            $table->enum('weight_type', ['ibs', 'kg']);
            $table->enum('shipping_type', ['b', 'n']); // business shipping or normal shipping
            $table->float('weight');
            $table->boolean('scheduled')->default(0);
            $table->text('content_description')->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('shipement_id')->references('id')->on('shipements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcels');
    }
}
