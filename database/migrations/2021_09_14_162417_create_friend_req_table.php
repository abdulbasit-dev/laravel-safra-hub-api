<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendReqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_req', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('person that send request');
            $table->foreignId('friend_req_id')->constrained('users')->comment('person that receive request');
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
        Schema::dropIfExists('friend_req');
    }
}
