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
            $table->id();
            $table->tinyInteger('type')->comment('Tipo de transação realizada.');
            $table->foreignId('from')->constrained('users')->comment('Quem realizou a transação.');
            $table->foreignId('to')->constrained('users')->comment('Quem foi o beneficiado da transação.');
            $table->decimal('requested_amount')->comment('Montante da transação.');
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
