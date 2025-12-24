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
        Schema::create('grn_images', function (Blueprint $table) {
            $table->id();
              $table->foreignId('grn_session_id')->constrained('grn_sessions')->cascadeOnDelete();
            $table->string('image_path'); // path to uploaded bill image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_images');
    }
};
