<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertDialogMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('advert_dialog_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dialog_id');
            $table->text('message');
        });

        Schema::table('advert_dialog_messages', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('dialog_id')->references('id')->on('advert_dialogs')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advert_dialog_messages');
    }
}
