<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_facts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('status', ['draft', 'trash', 'published']);
            $table->timestamp('imported_t');
            $table->text('url');
            $table->string('creator');
            $table->bigInteger('created_t');
            $table->bigInteger('last_modified_t');
            $table->string('product_name');
            $table->string('quantity');
            $table->string('brands');
            $table->text('categories');
            $table->text('labels')->nullable();
            $table->string('cities')->nullable();
            $table->text('purchase_places')->nullable();
            $table->string('stores')->nullable();
            $table->text('ingredients_text');
            $table->text('traces')->nullable();
            $table->string('serving_size');
            $table->float('serving_quantity');
            $table->integer('nutriscore_score');
            $table->string('nutriscore_grade');
            $table->string('main_category');
            $table->text('image_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_facts');
    }
};
