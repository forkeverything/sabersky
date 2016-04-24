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

            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('state');
            $table->string('country');
            $table->string('zip');
            $table->string('phone');

            $table->boolean('primary')->default(0);

            // Each can only have 1 primary address
            $table->unique(['owner_id', 'owner_type', 'primary']);

            $table->integer('owner_id')->unsigned();
            $table->string('owner_type');
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
