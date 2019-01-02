<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->integer('mobile')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('nationality')->nullable();
            $table->text('bio')->nullable();
            $table->string('image')->nullable();
            $table->integer('actor_id')->unsigned();
            $table->tinyInteger('isVerify')->default(0);
            $table->rememberToken();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('actor_id')->references('id')->on('actors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
