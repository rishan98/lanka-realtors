<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('bio');
            $table->string('company_logo_path')->nullable()->after('avatar_path');
            $table->unsignedSmallInteger('operating_since_year')->nullable()->after('company_logo_path');
            $table->unsignedInteger('buyers_served_estimate')->nullable()->after('operating_since_year');
            $table->boolean('is_preferred')->default(false)->after('buyers_served_estimate');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_path',
                'company_logo_path',
                'operating_since_year',
                'buyers_served_estimate',
                'is_preferred',
            ]);
        });
    }
}
