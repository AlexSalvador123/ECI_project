<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key column
            $table->string('name'); // Name of the account holder or company
            $table->string('company')->nullable(); // Company name, nullable if not applicable
            $table->string('external_reference')->unique(); // External reference, unique constraint
            $table->timestamps(); // Adds created_at and updated_at columns
            $table->softDeletes(); // Adds a deleted_at column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
