<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadCountsToListingsTable extends Migration
{
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->unsignedInteger('phone_lead_count')->default(0)->after('view_count');
            $table->unsignedInteger('email_lead_count')->default(0)->after('phone_lead_count');
        });
    }

    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['phone_lead_count', 'email_lead_count']);
        });
    }
}
