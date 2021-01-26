<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('subject');
            $table->text('content');
            $table->string('status', 16);
            $table->timestamps();
        });

        Schema::table('ticket_tickets', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });


        Schema::create('ticket_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status', 16);
            $table->timestamps();
        });

        Schema::table('ticket_statuses', function (Blueprint $table) {

            $table->foreign('ticket_id')->references('id')->on('ticket_tickets')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('ticket_tickets')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    public function down()
    {

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('ticket_messages');

        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('ticket_statuses');

        Schema::table('ticket_tickets', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('ticket_tickets');
    }
}
