<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no')->nullable();
            $table->integer('user_id');
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('party_id')->unsigned();
            //$table->enum('payment_type', ['cash','check']);
            //$table->string('check_number')->nullable;
            $table->float('total_amount', 8,2);
            $table->string('date');
            $table->enum('purchase_product_type',['Finish Goods','Raw Materials']);
            $table->timestamps();
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
        Schema::dropIfExists('product_purchases');
    }
}
