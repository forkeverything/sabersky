<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('contact_person')->nullable();
            $table->string('phone');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('zip');
            $table->string('state');

            $table->boolean('primary')->default(0);

            $table->integer('owner_id')->unsigned();
            $table->string('owner_type');

            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addresses');
    }
}
