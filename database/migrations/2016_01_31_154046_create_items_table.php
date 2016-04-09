<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('sku')->nullable();
            $table->string('brand')->nullable();
            $table->string('name');
            $table->text('specification');

            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->unique(['brand', 'name', 'company_id']);    // Brand-Name Combination has to be unique per. Company
            $table->unique(['sku', 'company_id']);              // SKU has to be unique per. Company
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
