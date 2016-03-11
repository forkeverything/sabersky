<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('limit')->nullable();

            $table->integer('property_id')->unsigned();
            $table->integer('trigger_id')->unsigned();
            $table->integer('company_id')->unsigned();
//
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('trigger_id')->references('id')->on('triggers')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rules');
    }
}
