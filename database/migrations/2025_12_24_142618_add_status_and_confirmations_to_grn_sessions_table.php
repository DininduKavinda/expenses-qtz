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
        Schema::table('grn_sessions', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('session_date'); // pending, confirmed, rejected
            $table->foreignId('confirmed_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->dateTime('confirmed_at')->nullable()->after('confirmed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grn_sessions', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['status', 'confirmed_by', 'confirmed_at']);
        });
    }
};
