<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('arrival_at');
            $table->timestamp('departure_at');
            $table->integer('jml_hari');
            $table->double('diskon')->nullable();
            $table->double('subtotal');
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('restrict');
            $table->foreign('guest_id')->references('id')->on('trx_guests')->onDelete('restrict');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
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
        Schema::dropIfExists('transactions');
    }
}
