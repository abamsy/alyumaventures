<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waybills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('share_id')->constrained()
                    ->onDelete('cascade');
            $table->date('date');
            $table->unsignedTinyInteger('quantity')->nullable();
            $table->unsignedSmallInteger('bags')->nullable();
            $table->string('driver');
            $table->string('phone');
            $table->string('truck');
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
        Schema::dropIfExists('waybills');
    }
}
