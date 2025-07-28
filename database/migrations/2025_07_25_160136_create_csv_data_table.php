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
        Schema::create('csv_data', function (Blueprint $table) {
            $table->id();
            $table->integer('unique_num')->unique();
            $table->text('title');
            $table->text('description');
            $table->text('opt_text')->nullable();
            $table->text('filename');
            $table->timestamp('uploaded_at');
            $table->text('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_data');
    }
};
