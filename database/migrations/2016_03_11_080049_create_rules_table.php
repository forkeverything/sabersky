<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesTable extends Migration
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

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('countries');

            $table->integer('rule_property_id')->unsigned();
            $table->integer('rule_trigger_id')->unsigned();
            $table->integer('company_id')->unsigned();

            $table->foreign('rule_property_id')->references('id')->on('rule_properties')->onDelete('cascade');
            $table->foreign('rule_trigger_id')->references('id')->on('rule_triggers')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->unique(['currency_id', 'rule_property_id', 'rule_trigger_id', 'company_id'], 'unique_rule');

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
