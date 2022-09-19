<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ref_id');
            $table->string('purchase_invoice_no');
            $table->string('invoice_no');
            $table->integer('user_id');
            $table->bigInteger('store_id');
            $table->enum('type', ['purchase','sale','sale return','production']);
            $table->bigInteger('product_id')->unsigned();
            $table->integer('qty');
            $table->float('price',8,2);
            $table->float('sub_total',8,2);
            $table->float('discount_amount',8,2);
            $table->float('profit_amount',8,2);
            $table->string('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profits');
    }
}
