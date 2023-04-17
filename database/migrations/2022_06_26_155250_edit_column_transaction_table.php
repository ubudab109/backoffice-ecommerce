<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropForeign('transaction_product_id_foreign');
        });
        
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('qty');
            $table->dropColumn('price_transaction');
            $table->dropColumn('subtotal');
            $table->dropColumn('discount_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
