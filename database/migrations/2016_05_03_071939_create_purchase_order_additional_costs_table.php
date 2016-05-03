<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderAdditionalCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_additional_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name');         // - freight, discount, tax,  etc...
            $table->string('type');         // - fixed or percentage
            $table->integer('amount');      // - to be added or deducted (discount, negative values)

            $table->integer('purchase_order_id')->unsigned();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_order_additional_costs');
    }
}
