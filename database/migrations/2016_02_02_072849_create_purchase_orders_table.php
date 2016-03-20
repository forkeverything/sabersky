<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('status')->default('pending'); // pending, approved or rejected
            $table->boolean('submitted')->default(0);
            $table->float('total', '15', 2)->default(0);
            
            $table->integer('project_id')->unsigned();
            $table->integer('vendor_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_orders');
    }
}
