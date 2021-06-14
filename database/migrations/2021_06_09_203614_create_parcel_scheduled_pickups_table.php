<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelScheduledPickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcel_scheduled_pickups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parcel_id')->unsigned();
            $table->bigInteger('scheduled_pickup_id')->unsigned();
            $table->boolean('status');
            $table->timestamps();
            $table->foreign('parcel_id')->references('id')->on('parcels');
            $table->foreign('scheduled_pickup_id')->references('id')->on('scheduled_pickups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcel_scheduled_pickups');
    }
}
