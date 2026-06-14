<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agent_reviews', function (Blueprint $table) {
            $table->string('status', 16)->default('pending')->after('rating');
            $table->timestamp('moderated_at')->nullable()->after('status');
            $table->index(['agent_id', 'status', 'created_at']);
        });

        DB::table('agent_reviews')->update([
            'status' => 'approved',
            'moderated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('agent_reviews', function (Blueprint $table) {
            $table->dropIndex(['agent_id', 'status', 'created_at']);
            $table->dropColumn(['status', 'moderated_at']);
        });
    }
};
