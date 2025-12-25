<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('type')->default('crud')->after('user_id'); // crud, auth, command, system
            $table->json('metadata')->nullable()->after('new_values');
            $table->unsignedBigInteger('record_id')->nullable()->change();
            $table->string('table_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['type', 'metadata']);
            $table->unsignedBigInteger('record_id')->change();
            $table->string('table_name')->change();
        });
    }
};
