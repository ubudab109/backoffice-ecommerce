<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_admin', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name', 255);
            $table->foreignUuid('role_id', 'role_id_fk')->references(['id'])->on('role');
            $table->foreignUuid('user_id', 'user_id_fk')->references(['id'])->on('users');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_admin');
    }
}
