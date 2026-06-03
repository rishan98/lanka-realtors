<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_agent')->default(true)->after('password');
            $table->string('phone', 32)->nullable()->after('is_agent');
            $table->string('agency_name')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('agency_name');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_agent', 'phone', 'agency_name', 'bio']);
        });
    }
}
