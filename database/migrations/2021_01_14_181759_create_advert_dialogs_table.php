<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertDialogsTable extends Migration
{

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
        });
    }

    public function down()
    {
        Schema::dropIfExists('advert_dialogs');
    }
}
