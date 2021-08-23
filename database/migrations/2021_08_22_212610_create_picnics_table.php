<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicnicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'picnics',
            function (Blueprint $table) {
                $table->id();
                $table->string('location')->comment('it be the title of the picnic');
                $table->boolean('type')->comment('0 => public, 1 => private');
                $table->boolean('currency_type')->comment('0 => $, 1 => IQD');
                $table->text('description')->nullable();

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('picnics');
    }
}
