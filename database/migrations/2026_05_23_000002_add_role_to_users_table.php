<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 16)->default('agent')->after('password');
        });

        if (Schema::hasColumn('users', 'is_agent')) {
            DB::table('users')->where('is_agent', true)->update(['role' => 'agent']);
            DB::table('users')->where('is_agent', false)->update(['role' => 'owner']);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_agent');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_agent')->default(true)->after('password');
        });

        DB::table('users')->where('role', 'agent')->update(['is_agent' => true]);
        DB::table('users')->whereIn('role', ['owner', 'admin'])->update(['is_agent' => false]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }
}
