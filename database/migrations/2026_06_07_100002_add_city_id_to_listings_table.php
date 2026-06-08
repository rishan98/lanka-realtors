<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddCityIdToListingsTable extends Migration
{
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('property_subtype')->constrained()->nullOnDelete();
        });

        $names = DB::table('listings')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city');

        $sort = 0;
        foreach ($names as $name) {
            $slug = Str::slug($name);
            if ($slug === '') {
                continue;
            }

            $existing = DB::table('cities')->where('slug', $slug)->first();
            if (! $existing) {
                $cityId = DB::table('cities')->insertGetId([
                    'name' => $name,
                    'slug' => $slug,
                    'is_active' => true,
                    'sort_order' => $sort++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $cityId = $existing->id;
            }

            DB::table('listings')->where('city', $name)->update(['city_id' => $cityId]);
        }

        Schema::table('listings', function (Blueprint $table) {
            $table->index('city_id');
        });
    }

    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('city_id');
        });
    }
}
