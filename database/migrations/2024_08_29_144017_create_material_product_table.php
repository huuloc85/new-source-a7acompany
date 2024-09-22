<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_product', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');                      // Cột product_id
            $table->unsignedBigInteger('production_plans_id');          // Cột productplan_id
            $table->integer('quantity');                        // Cột quantity
            $table->integer('real_quantity');                   // Cột real_quantity
            $table->timestamps();                               // Tự động tạo created_at và updated_at

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('production_plans_id')->references('id')->on('production_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_product');
    }
}
