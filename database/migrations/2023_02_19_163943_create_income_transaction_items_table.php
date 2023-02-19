<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_transaction_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('income_transaction_id');
            $table->foreign('income_transaction_id')
                    ->references('id')
                    ->on('income_transactions')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')
                    ->references('id')
                    ->on('items')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_transaction_items');
    }
}
