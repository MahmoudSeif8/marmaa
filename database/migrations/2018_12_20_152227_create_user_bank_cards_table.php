<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBankCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('card_name');
            $table->integer('card_number');
            $table->tinyInteger('CVV');
            $table->string('expires');
            $table->integer('bank_card_type_id')->unsigned();
            $table->integer('user_payment_option_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bank_card_type_id')->references('id')->on('bank_card_types')->onDelete('cascade');
            $table->foreign('user_payment_option_id')->references('id')->on('user_payment_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bank_cards');
    }
}
