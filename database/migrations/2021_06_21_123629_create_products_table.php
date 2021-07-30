<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('barcode',25)->nullable();
            $table->string('mark',25)->nullable();
            $table->string('model',25)->nullable();
            $table->decimal('cost',10,2)->nullable()->default(0);
            $table->decimal('price',10,2)->nullable()->default(0);
            $table->integer('stock');
            $table->integer('alerts')
                  ->nullable()
                  ->default(10);
            $table->string('image',100)->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('subcategory_id')
                  ->nullable()
                  ->constrained('sub_categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
