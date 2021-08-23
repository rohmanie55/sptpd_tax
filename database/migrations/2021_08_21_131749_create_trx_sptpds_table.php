<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxSptpdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_sptpds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('periode', 6);
            $table->string('no_bill', 50);
            $table->string('status', 10);
            $table->double('total');
            $table->string('deskripsi')->nullable();
            $table->unsignedBigInteger('create_by');
            $table->foreign('create_by')->references('id')->on('users')->onDelete('restrict');
            $table->unsignedBigInteger('approve_by');
            $table->foreign('approve_by')->references('id')->on('users')->onDelete('restrict');
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
        Schema::dropIfExists('trx_sptpds');
    }
}
