<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertDialogsTable extends Migration
{
    public function up()
    {
        Schema::create('advert_dialogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('advert_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedInteger('user_new_messages');
            $table->unsignedInteger('client_new_messages');
        });

        Schema::table('advert_dialogs', function (Blueprint $table) {
            $table->foreign('advert_id')->references('id')->on('advert_adverts')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advert_dialogs');
    }
}
