<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionReceiverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_receiver', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('customer_id')->nullable()->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('transaction_id')->constrained('transaction')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_whatsapp')->nullable();
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
        Schema::dropIfExists('transaction_receiver');
    }
}
