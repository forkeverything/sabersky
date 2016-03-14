<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rule_triggers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('label');

            $table->boolean('has_limit')->default(0);

            $table->integer('rule_property_id')->unsigned();
            $table->foreign('rule_property_id')->references('id')->on('rule_properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rule_triggers');
    }
}
