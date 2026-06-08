<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run()
    {
        $locations = require database_path('data/sri_lanka_locations.php');

        DB::transaction(function () use ($locations) {
            $sort = 0;

            foreach ($locations as $districtData) {
                $district = City::updateOrCreate(
                    ['name' => $districtData['name'], 'parent_id' => null],
                    [
                        'latitude' => $districtData['latitude'] ?? null,
                        'longitude' => $districtData['longitude'] ?? null,
                        'is_active' => true,
                        'sort_order' => $sort++,
                    ]
                );

                $areaSort = 0;
                foreach ($districtData['areas'] as $areaData) {
                    City::updateOrCreate(
                        [
                            'name' => $areaData['name'],
                            'parent_id' => $district->id,
                        ],
                        [
                            'latitude' => $areaData['latitude'] ?? $district->latitude,
                            'longitude' => $areaData['longitude'] ?? $district->longitude,
                            'is_active' => true,
                            'sort_order' => $areaSort++,
                        ]
                    );
                }
            }

            $this->deactivateLegacyFlatCities();
            $this->relinkListings();
        });
    }

    protected function deactivateLegacyFlatCities(): void
    {
        $legacyIds = City::query()
            ->whereNull('parent_id')
            ->whereDoesntHave('children')
            ->pluck('id');

        if ($legacyIds->isNotEmpty()) {
            City::query()->whereIn('id', $legacyIds)->update(['is_active' => false]);
        }
    }

    protected function relinkListings(): void
    {
        Listing::query()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->chunkById(100, function ($listings) {
                foreach ($listings as $listing) {
                    $city = City::query()
                        ->where('name', $listing->city)
                        ->whereNotNull('parent_id')
                        ->first();

                    if (! $city) {
                        $city = City::query()
                            ->where('name', $listing->city)
                            ->whereNull('parent_id')
                            ->whereHas('children')
                            ->first();
                    }

                    if ($city && $listing->city_id !== $city->id) {
                        $listing->update(['city_id' => $city->id]);
                    }
                }
            });
    }
}
