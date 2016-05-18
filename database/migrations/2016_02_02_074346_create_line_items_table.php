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
            $table->dateTime('payable')->nullable();
            $table->dateTime('delivery')->nullable();
            $table->boolean('paid')->default(0);
            $table->string('status')->default('unreceived'); // 'unreceived', 'accepted', 'returned'

            // Orders
            $table->integer('purchase_order_id')->unsigned();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');

            // Requests
            $table->integer('purchase_request_id')->unsigned();
            $table->foreign('purchase_request_id')->references('id')->on('purchase_requests');

            /*
             * Line Items are like Pivot-tables between Orders and the Requests they fulfill. Except
             * we add on a few extra fields as well such as: quantity, price (unit), payable (date),
             * delivery (date), paid (bool), status (strings / enum).
             */

            // Avoid multiples
            $table->unique(['purchase_order_id', 'purchase_request_id']);

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
