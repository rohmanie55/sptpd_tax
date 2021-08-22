<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionFbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_fbs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('tgl_trx');
            $table->integer('qty');
            $table->double('total');
            $table->unsignedBigInteger('trx_id');
            $table->unsignedBigInteger('fab_id');
            $table->foreign('trx_id')->references('id')->on('transactions')->onDelete('restrict');
            $table->foreign('fab_id')->references('id')->on('food_baverages')->onDelete('restrict');
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
        Schema::dropIfExists('transaction_fbs');
    }
}
