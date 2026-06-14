<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_carousel_banners', function (Blueprint $table) {
            $table->string('context', 32)->default('homepage')->after('id');
        });

        DB::table('hero_carousel_banners')->update(['context' => 'homepage']);

        Schema::table('hero_carousel_banners', function (Blueprint $table) {
            $table->dropUnique(['position']);
            $table->unique(['context', 'position']);
        });
    }

    public function down(): void
    {
        Schema::table('hero_carousel_banners', function (Blueprint $table) {
            $table->dropUnique(['context', 'position']);
            $table->unique(['position']);
            $table->dropColumn('context');
        });
    }
};
