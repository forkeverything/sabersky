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

            // Status of Order
            $table->string('status')->default('pending'); // pending, approved or rejected

            $table->integer('number');  // Sequential numbering per. company

            // Vendor
                // id
                $table->integer('vendor_id')->unsigned();
                $table->foreign('vendor_id')->references('id')->on('vendors');
                // Address - Can be NULL depending on Company Settings
                $table->integer('vendor_address_id')->unsigned()->nullable();
                $table->foreign('vendor_address_id')->references('id')->on('addresses')->onDelete('set null');
                // Account - Can be NULL depending on Company Settings
                $table->integer('vendor_bank_account_id')->unsigned()->nullable();
                $table->foreign('vendor_bank_account_id')->references('id')->on('bank_accounts');

            // Purchase Info
                // Currency
                $table->integer('currency_id')->unsigned()->default('840');
                $table->foreign('currency_id')->references('id')->on('countries');
                // Compulsory billing address - NULL at first, attach after creating model
                $table->integer('billing_address_id')->unsigned()->nullable();
                $table->foreign('billing_address_id')->references('id')->on('addresses')->onDelete('set null');
                // Either points to same Address as Billing or different Address model
                $table->integer('shipping_address_id')->unsigned()->nullable();
                $table->foreign('shipping_address_id')->references('id')->on('addresses')->onDelete('set null');

            // Summary - fields we calculate and store so they can be retrieved, sorted, and filtered faster than calculating dynamically
            $table->float('subtotal', 15, 2);
            $table->float('total', 15, 2);

            // User that submitted PO
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            // Company
            $table->integer('company_id')->unsigned();
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
        Schema::drop('purchase_orders');
    }
}
