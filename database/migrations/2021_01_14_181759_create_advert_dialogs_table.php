<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertDialogsTable extends Migration
{
<<<<<<< HEAD
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
=======

* @property int $id
* @property int $advert_id
* @property int $user_id
* @property int $client_id
* @property Carbon $created_at
* @property Carbon $updated_at
* @property int $user_new_messages
* @property int $client_new_messages


    public function up()
    {
        Schema::create('advert_dialogs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
>>>>>>> origin/dev
        });
    }

    public function down()
    {
        Schema::dropIfExists('advert_dialogs');
    }
}
