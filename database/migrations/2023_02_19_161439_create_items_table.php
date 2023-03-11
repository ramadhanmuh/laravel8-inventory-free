<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->text('description');
            $table->uuid('category_id');
            $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->uuid('brand_id');
            $table->foreign('brand_id')
                    ->references('id')
                    ->on('brands')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->uuid('unit_of_measurement_id');
            $table->foreign('unit_of_measurement_id')
                    ->references('id')
                    ->on('unit_of_measurements')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->decimal('price', 10, 2);

            $table->text('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
