<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration
{
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 14, 2)->nullable();
            $table->string('currency', 8)->default('LKR');
            $table->string('listing_kind', 32);
            $table->string('property_subtype', 64);
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->string('land_size')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('status', 16)->default('published');
            $table->timestamps();

            $table->index(['listing_kind', 'property_subtype']);
            $table->index(['status', 'listing_kind']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
