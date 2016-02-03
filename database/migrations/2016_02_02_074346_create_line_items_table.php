<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('quantity');
            $table->float('price', 15, 2);
            $table->dateTime('payable');
            $table->dateTime('delivery');
            $table->boolean('delivered')->default(0);
            $table->boolean('paid')->default(0);
            $table->string('status')->default('unreceived'); // 'unreceived', 'accepted', 'rejected'

            $table->integer('purchase_order_id')->unsigned();
            $table->integer('purchase_request_id')->unsigned();

            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->foreign('purchase_request_id')->references('id')->on('purchase_requests');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('line_items');
    }
}
