<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();;
            $table->string('menu_title')->nullable();
            $table->string('slug')->unique();;
            $table->mediumText('content');
            $table->text('description')->nullable();
            $table->timestamps();
            NestedSet::columns($table);
        });
    }
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
