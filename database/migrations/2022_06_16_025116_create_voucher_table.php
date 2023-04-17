<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->double('minimum_shop_price')->default(0);
            $table->enum('discount_type',['discount','fixed']);
            $table->double('discount_value')->default(0);
            $table->double('discount_maximal')->default(0);
            $table->string('code')->unique();
            $table->integer('kuota')->default(0);
            $table->date('date_start_voucher');
            $table->date('date_end_voucher');
            $table->enum('voucher_type',['price_deducted']);
            $table->enum('status',['0','1'])->default('0')->comment('0 inactive, 1 active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher');
    }
}
