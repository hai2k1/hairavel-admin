<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('nickname', 20)->nullable()->comment('nickname');
            $table->string('username', 20)->comment('username');
            $table->string('password', 255)->comment('password');
            $table->rememberToken()->comment('token');
            $table->string('avatar', 255)->nullable()->comment('avatar');
            $table->boolean('status')->default(1)->index('status')->comment('status');
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
        Schema::dropIfExists('system_user');
    }
}
