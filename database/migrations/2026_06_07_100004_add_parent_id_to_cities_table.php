<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToCitiesTable extends Migration
{
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained('cities')
                ->nullOnDelete();

            $table->index(['parent_id', 'is_active', 'sort_order']);
        });
    }

    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
        });
    }
}
