<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('product_id')->constrained('product')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('voucher_id')->nullable()->constrained('voucher')->nullOnDelete()->cascadeOnUpdate();
            $table->text('shipping_address')->nullable();
            $table->double('shipping_fee')->default(0);
            $table->integer('qty')->default(0);
            $table->string('no_invoice');
            $table->dateTime('transaction_date');
            $table->double('price_transaction')->default(0);
            $table->double('discount_price')->default(0);
            $table->enum('payment_type',['sistem','cod']);
            $table->enum('shipping_type',['pickup','delivered']);
            $table->double('subtotal')->default(0)->comment('sub total from item');
            $table->double('total_price')->default(0)->comment('total price with shipping fee');
            $table->string('city')->nullable();
            $table->text('note')->nullable();
            $table->enum('status',['0','1','2','3','4','5','6'])->comment('0: menunggu pembayaran, 1:Pembayaran COD, 2:Menunggu Konfirmasi, 3:Pesanan Diproses, 4:Pesanan Dikirim, 5:Pesanan Selesai, 6:Pesanan Dibatalkan')->default('0');
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
        Schema::dropIfExists('transaction');
    }
}
