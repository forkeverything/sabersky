<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_phone')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('swift')->nullable();


            // Primary
            $table->boolean('primary')->default(0);

            // For soft-deletes
            $table->boolean('active')->default(1);
            


            $table->integer('vendor_id')->unsigned();
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
        Schema::drop('bank_accounts');
    }
}
