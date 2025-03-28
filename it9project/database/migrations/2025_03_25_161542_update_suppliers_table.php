<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('suppliers', function (Blueprint $table) {
        // Rename 'contact' to 'contact_number'
        $table->renameColumn('contact', 'contact_number');
        // Modify 'contact_number' to accept only 12 digits
        $table->string('contact_number', 12)->nullable()->change();
        // Add 'location' field
        $table->string('location')->nullable();
    });
}

public function down()
{
    Schema::table('suppliers', function (Blueprint $table) {
        // Reverse changes
        $table->renameColumn('contact_number', 'contact');
        $table->string('contact', 50)->nullable()->change();
        $table->dropColumn('location');
    });
}
};
