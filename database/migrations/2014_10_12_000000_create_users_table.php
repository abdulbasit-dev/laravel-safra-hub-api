<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable()->comment('fname and lname sperated by space');
            $table->string('email',100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('qrcode')->nullable();
            $table->text('bio')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1=>male 2=>female 3=>other');
            $table->dateTime('birthday')->nullable();
            $table->boolean('is_available')->default(true)->comment('whether currently in picnic or not');
            $table->string("otp")->nullable();
            $table->boolean("otp_verified")->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
