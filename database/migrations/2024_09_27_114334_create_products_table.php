<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key column
            $table->string('sku')->unique(); // Unique SKU (Stock Keeping Unit) identifier
            $table->string('name'); // Product name
            $table->text('description')->nullable(); // Product description, can be nullable
            $table->decimal('price', 10, 2); // Price of the product, with up to 10 digits and 2 decimal places
            $table->timestamps(); // Adds created_at and updated_at columns
            $table->softDeletes(); // Adds a deleted_at column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
