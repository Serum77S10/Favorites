<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavorites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('microposts_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            // 外部キー設定
            $table->foreign('microposts_id')->references('id')->on('microposts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // user_idとmicroposts_idの組み合わせの重複を許さない
            $table->unique(['microposts_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_favorites');
        Schema::disableForeignKeyConstraints();
    }
}
