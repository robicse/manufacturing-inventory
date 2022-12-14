<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no');
            $table->string('purchase_invoice_no');
            $table->bigInteger('product_purchase_id')->unsigned();
            $table->integer('user_id');
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('party_id')->nullable()->unsigned();
            $table->enum('payment_type', ['cash','online'])->nullable();
            $table->enum('discount_type',['flat','percentage'])->nullable();
            $table->float('discount_amount', 8,2)->nullable();
            $table->float('total_amount', 8,2)->nullable();
            $table->timestamps();
            $table->foreign('product_purchase_id')->references('id')->on('product_purchases')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_purchase_returns');
    }
}
