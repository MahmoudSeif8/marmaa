<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB' ;
            $table->increments('id');
            $table->string('name');
            $table->integer('owner_location_id')->unsigned();
            $table->integer('sport_type_id')->unsigned();
            $table->integer('field_size_id')->nullable()->unsigned();
            $table->integer('playground_type_id')->unsigned();
            $table->string('license')->nullable();
            $table->tinyInteger('forWomen')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('owner_location_id')->references('id')->on('owner_locations')->onDelete('cascade');
            $table->foreign('sport_type_id')->references('id')->on('sport_types')->onDelete('cascade');
            $table->foreign('field_size_id')->references('id')->on('field_sizes')->onDelete('cascade');
            $table->foreign('playground_type_id')->references('id')->on('playground_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
