<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition()
    {
        $kind = $this->faker->randomElement(['sale', 'rental', 'invest', 'wanted']);
        $subtypes = array_keys(config('listing.kinds.'.$kind.'.subtypes', ['house' => 'House']));
        $subtype = $this->faker->randomElement($subtypes);
        $isLand = $subtype === 'land';

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'contact_number' => '+94 77 '.$this->faker->numerify('### ####'),
            'price' => $kind === 'wanted' ? null : $this->faker->numberBetween(5000000, 80000000),
            'currency' => 'LKR',
            'listing_kind' => $kind,
            'property_subtype' => $subtype,
            'city' => $this->faker->randomElement(['Colombo', 'Kandy', 'Galle', 'Negombo']),
            'area' => $this->faker->streetName(),
            'latitude' => $this->faker->latitude(6.0, 7.5),
            'longitude' => $this->faker->longitude(79.8, 80.8),
            'bedrooms' => $isLand ? null : $this->faker->numberBetween(1, 5),
            'bathrooms' => $isLand ? null : $this->faker->numberBetween(1, 4),
            'built_area_sqft' => $isLand ? null : $this->faker->numberBetween(600, 4500),
            'floors' => $isLand ? null : $this->faker->numberBetween(1, 3),
            'furnishing_status' => $isLand ? null : $this->faker->randomElement(['furnished', 'semi_furnished', 'unfurnished']),
            'parking_available' => $isLand ? null : $this->faker->boolean(70),
            'land_size' => $isLand ? (string) $this->faker->numberBetween(5, 50) : null,
            'land_size_unit' => $isLand ? $this->faker->randomElement(['perches', 'acres']) : null,
            'status' => 'published',
        ];
    }

    public function published()
    {
        return $this->state(['status' => 'published']);
    }

    public function draft()
    {
        return $this->state(['status' => 'draft']);
    }

    public function kind(string $kind)
    {
        return $this->state(function () use ($kind) {
            $subtypes = array_keys(config('listing.kinds.'.$kind.'.subtypes', ['house' => 'House']));

            return [
                'listing_kind' => $kind,
                'property_subtype' => $this->faker->randomElement($subtypes),
            ];
        });
    }
}
