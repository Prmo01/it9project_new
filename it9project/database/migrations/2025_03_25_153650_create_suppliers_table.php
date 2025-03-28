<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('supplier_id'); // Primary key, auto-increment
            $table->string('name', 100)->nullable(false); // NOT NULL - Required
            $table->string('contact', 50)->nullable(); // NULLABLE - Optional
            $table->timestamps(); // created_at and updated_at (Nullable)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
