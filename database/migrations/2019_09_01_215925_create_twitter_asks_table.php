<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitterAsksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_asks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tweet_id', 20)->index();
            $table->string('tweet', 280);
            $table->string('user')->nullable();
            $table->boolean('is_replied')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('twitter_asks');
    }
}
