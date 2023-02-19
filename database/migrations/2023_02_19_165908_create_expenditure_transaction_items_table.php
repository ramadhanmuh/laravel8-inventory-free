<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenditureTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditure_transaction_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('expenditure_transaction_id');
            $table->foreign('expenditure_transaction_id')
                    ->references('id')
                    ->on('expenditure_transactions')
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
        Schema::dropIfExists('expenditure_transaction_items');
    }
}
