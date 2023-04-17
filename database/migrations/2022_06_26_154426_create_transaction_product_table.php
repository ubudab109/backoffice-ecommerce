<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_product', function (Blueprint $table) {
            $table->foreignUuid('id')->primary();
            $table->foreignUuid('transaction_id')->constrained('transaction')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('product_id')->constrained('product')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('qty')->default(0);
            $table->double('item_price')->default(0)->comment('Price Item per One');
            $table->double('discount_price')->default(0)->comment('Discount price if has a discount in item');
            $table->double('subtotal')->default(0)->comment('subtotal from (price * qty) - discount');
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
        Schema::dropIfExists('transaction_product');
    }
}
