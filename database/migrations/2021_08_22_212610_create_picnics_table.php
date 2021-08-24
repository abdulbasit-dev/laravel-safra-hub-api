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
                $table->integer('type')->comment('0 => public, 1 => private, 3 => closed');
                $table->boolean('currency_type')->comment('0 => $, 1 => IQD');
                $table->text('description')->nullable();
                $table->foreignId('created_by')->constrained('users');
                $table->integer('no_of_people')->nullable();
                $table->string('code',10)->nullable();
                $table->string('qrcode')->nullable();
                $table->text('google_drive_link')->nullable();
                $table->float('item_cost')->nullable();
                $table->float('gas_cost')->nullable();
                $table->float('total_cost')->nullable();
                $table->dateTime('start_at');
                $table->dateTime('end_at');
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
