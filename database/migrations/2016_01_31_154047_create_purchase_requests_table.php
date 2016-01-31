<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('quantity');
            $table->dateTime('due');
            $table->string('state')->default('open'); // State can be 'open', 'fulfilled', 'cancelled'
            $table->boolean('urgent')->default(0);

            $table->integer('item_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned();

            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_requests');
    }
}
