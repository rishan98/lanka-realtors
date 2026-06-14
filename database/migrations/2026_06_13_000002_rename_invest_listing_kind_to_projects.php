<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameInvestListingKindToProjects extends Migration
{
    public function up()
    {
        DB::table('listings')
            ->where('listing_kind', 'invest')
            ->update(['listing_kind' => 'projects']);

        DB::table('hero_carousel_banners')
            ->where('context', 'invest')
            ->update(['context' => 'projects']);
    }

    public function down()
    {
        DB::table('listings')
            ->where('listing_kind', 'projects')
            ->update(['listing_kind' => 'invest']);

        DB::table('hero_carousel_banners')
            ->where('context', 'projects')
            ->update(['context' => 'invest']);
    }
}
