<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListingDetailFields extends Migration
{
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('contact_number', 32)->nullable()->after('description');
            $table->unsignedSmallInteger('floors')->nullable()->after('bathrooms');
            $table->string('furnishing_status', 32)->nullable()->after('floors');
            $table->boolean('parking_available')->nullable()->after('furnishing_status');
            $table->string('land_size_unit', 16)->nullable()->after('land_size');
            $table->unsignedSmallInteger('advance_payment_months')->nullable()->after('built_area_sqft');
            $table->unsignedSmallInteger('deposit_months')->nullable()->after('advance_payment_months');
            $table->boolean('short_term_available')->nullable()->after('deposit_months');
            $table->boolean('bills_included')->nullable()->after('short_term_available');
            $table->json('images')->nullable()->after('featured_image');
        });
    }

    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_number',
                'floors',
                'furnishing_status',
                'parking_available',
                'land_size_unit',
                'advance_payment_months',
                'deposit_months',
                'short_term_available',
                'bills_included',
                'images',
            ]);
        });
    }
}
