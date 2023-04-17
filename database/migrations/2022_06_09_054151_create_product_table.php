<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('creator_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('code_product')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained('category_product')->nullOnDelete()->cascadeOnUpdate();
            $table->longText('description')->nullable();
            $table->enum('promo_status',['0','1'])->default('0');
            $table->enum('promo_type',['discount','fixed'])->nullable();
            $table->double('price')->default(0);
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
        Schema::dropIfExists('product');
    }
}
