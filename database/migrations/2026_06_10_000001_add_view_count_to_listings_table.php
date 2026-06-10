<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewCountToListingsTable extends Migration
{
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->unsignedInteger('view_count')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
}
