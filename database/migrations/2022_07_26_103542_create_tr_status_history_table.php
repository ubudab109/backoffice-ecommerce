<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrStatusHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_status_history', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignUuid('transaction_id')->constrained('transaction')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('trx_status_id');
            $table->string('trx_status_text');
            $table->json('status_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_status_history');
    }
}
