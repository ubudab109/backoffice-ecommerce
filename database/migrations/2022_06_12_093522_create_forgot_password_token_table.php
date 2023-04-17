<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForgotPasswordTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forgot_password_token', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('token');
            $table->string('email');
            $table->dateTime('expired_date');
            $table->enum('status',['0','1','2'])->comment('0 pending, 1 confirmed, 2 failed')->default('0');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('forgot_password_token');
    }
}
