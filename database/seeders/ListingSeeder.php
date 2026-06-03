<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    protected $locations = [
        ['city' => 'Colombo', 'area' => 'Bambalapitiya', 'lat' => 6.8881, 'lng' => 79.8607],
        ['city' => 'Colombo', 'area' => 'Nugegoda', 'lat' => 6.8649, 'lng' => 79.8997],
        ['city' => 'Colombo', 'area' => 'Dehiwala', 'lat' => 6.8561, 'lng' => 79.8612],
        ['city' => 'Mount Lavinia', 'area' => 'Beach Road', 'lat' => 6.8380, 'lng' => 79.8636],
        ['city' => 'Kandy', 'area' => 'Peradeniya', 'lat' => 7.2718, 'lng' => 80.5956],
        ['city' => 'Galle', 'area' => 'Fort', 'lat' => 6.0329, 'lng' => 80.2170],
        ['city' => 'Negombo', 'area' => 'Lewis Place', 'lat' => 7.2088, 'lng' => 79.8358],
        ['city' => 'Ja-Ela', 'area' => 'Town', 'lat' => 7.0748, 'lng' => 79.8910],
    ];

    public function run()
    {
        $seedEmails = array_merge(
            [
                'nipun.perera@seed.test',
                'sanduni.fernando@seed.test',
                'ravi.silva@seed.test',
                'anuki.jayawardena@seed.test',
                'dilan.rathnayake@seed.test',
                'tharushi.wickramasinghe@seed.test',
                'owner.kamal@seed.test',
                'owner.malini@seed.test',
                'owner.raj@seed.test',
            ]
        );

        $users = User::whereIn('email', $seedEmails)->get()->keyBy('email');

        if ($users->isEmpty()) {
            $this->command->warn('No seed users found. Run UserSeeder first.');

            return;
        }

        Listing::whereIn('user_id', $users->pluck('id'))->delete();

        $templates = $this->listingTemplates();

        foreach ($templates as $template) {
            $user = $users->get($template['user_email']);
            if (! $user) {
                continue;
            }

            unset($template['user_email']);
            $loc = $this->locations[array_rand($this->locations)];

            Listing::create(array_merge(
                $this->baseLocation($loc, $user),
                $template
            ));
        }
    }

    protected function baseLocation(array $loc, User $user): array
    {
        return [
            'user_id' => $user->id,
            'city' => $loc['city'],
            'area' => $loc['area'],
            'latitude' => $loc['lat'] + (mt_rand(-50, 50) / 10000),
            'longitude' => $loc['lng'] + (mt_rand(-50, 50) / 10000),
            'currency' => 'LKR',
            'contact_number' => $user->phone ?: '+94 77 000 0000',
            'status' => 'published',
        ];
    }

    protected function listingTemplates(): array
    {
        return [
            // —— Sales ——
            $this->residential('nipun.perera@seed.test', 'sale', 'house', 'Spacious 4BR family home near schools', 28500000, 4, 3, 2400),
            $this->residential('nipun.perera@seed.test', 'sale', 'apartment', 'Modern 3BR apartment with city views', 42000000, 3, 2, 1650),
            $this->land('nipun.perera@seed.test', 'sale', 'Residential plot in quiet lane', 18500000, '12', 'perches'),
            $this->residential('sanduni.fernando@seed.test', 'sale', 'villa', 'Luxury villa with pool and garden', 125000000, 5, 5, 5200),
            $this->residential('sanduni.fernando@seed.test', 'sale', 'bungalow', 'Colonial-style bungalow fully renovated', 68000000, 4, 3, 3100),
            $this->commercial('dilan.rathnayake@seed.test', 'sale', 'Prime retail unit on high street', 95000000),
            $this->residential('ravi.silva@seed.test', 'sale', 'house', 'Beach-area 3BR house with rooftop terrace', 55000000, 3, 2, 2100),

            // —— Rentals ——
            $this->rental('sanduni.fernando@seed.test', 'apartment', 'Furnished 2BR apartment — short walk to Galle Face', 185000, 2, 2, 1100, true, true),
            $this->rental('sanduni.fernando@seed.test', 'rooms', 'Single room in shared villa — bills included', 45000, 1, 1, 280, false, true),
            $this->rental('tharushi.wickramasinghe@seed.test', 'house', '3BR house for long-term rent in Negombo', 95000, 3, 2, 1800, false, false),
            $this->rental('nipun.perera@seed.test', 'annexe', 'Ground-floor annexe with separate entrance', 65000, 2, 1, 650, false, false),

            // —— Invest ——
            $this->residential('ravi.silva@seed.test', 'invest', 'land', 'Coastal land parcel — tourism potential', 22000000, null, null, null, '8', 'perches'),
            $this->residential('dilan.rathnayake@seed.test', 'invest', 'commercial', 'Mixed-use building — strong rental yield', 180000000, null, null, 8500),
            $this->residential('anuki.jayawardena@seed.test', 'invest', 'apartment', 'Boutique apartment block — pre-launch units', 14500000, 2, 2, 980),

            // —— Wanted ——
            $this->wanted('owner.kamal@seed.test', 'house', 'Looking for 3–4BR house in Colombo suburbs', 'Budget around 35M LKR. Prefer Nugegoda, Maharagama, or Dehiwala.'),
            $this->wanted('owner.malini@seed.test', 'apartment', 'Wanted: 2BR apartment for rent in Colombo 03–05', 'Long-term lease. Parking required. Max 200k/month.'),
            $this->wanted('owner.raj@seed.test', 'land', 'Seeking 10–20 perch residential land near Kandy', 'Flat terrain, road access essential.'),

            // —— Owner direct listings ——
            $this->residential('owner.kamal@seed.test', 'sale', 'house', 'Owner sale — well-maintained 3BR in Ja-Ela', 22000000, 3, 2, 1900),
            $this->rental('owner.malini@seed.test', 'apartment', 'Owner rent — 2BR near Negombo lagoon', 75000, 2, 1, 1050, false, false),
            $this->land('owner.raj@seed.test', 'sale', 'Bare land — ideal for holiday home', 9800000, '15', 'perches'),

            // —— Draft (agent) ——
            array_merge(
                $this->residential('anuki.jayawardena@seed.test', 'sale', 'bungalow', 'Hill-country bungalow — draft listing', 42000000, 3, 2, 2800),
                ['status' => 'draft']
            ),
        ];
    }

    protected function residential(
        string $userEmail,
        string $kind,
        string $subtype,
        string $title,
        ?float $price,
        ?int $bedrooms,
        ?int $bathrooms,
        ?int $sqft,
        ?string $landSize = null,
        ?string $landUnit = null
    ): array {
        $isLand = $subtype === 'land';

        $data = [
            'user_email' => $userEmail,
            'listing_kind' => $kind,
            'property_subtype' => $subtype,
            'title' => $title,
            'description' => $this->description($title, $kind),
            'price' => $price,
            'bedrooms' => $isLand ? null : $bedrooms,
            'bathrooms' => $isLand ? null : $bathrooms,
            'built_area_sqft' => $isLand ? null : $sqft,
            'floors' => $isLand ? null : ($sqft && $sqft > 2000 ? 2 : 1),
            'furnishing_status' => $isLand ? null : ($kind === 'rental' ? 'furnished' : 'semi_furnished'),
            'parking_available' => $isLand ? null : true,
            'land_size' => $isLand ? $landSize : ($landSize ?: null),
            'land_size_unit' => $isLand ? $landUnit : null,
        ];

        return $data;
    }

    protected function land(string $userEmail, string $kind, string $title, float $price, string $size, string $unit): array
    {
        return [
            'user_email' => $userEmail,
            'listing_kind' => $kind,
            'property_subtype' => 'land',
            'title' => $title,
            'description' => $this->description($title, $kind),
            'price' => $price,
            'land_size' => $size,
            'land_size_unit' => $unit,
            'bedrooms' => null,
            'bathrooms' => null,
            'built_area_sqft' => null,
            'floors' => null,
            'furnishing_status' => null,
            'parking_available' => null,
        ];
    }

    protected function commercial(string $userEmail, string $kind, string $title, float $price): array
    {
        return [
            'user_email' => $userEmail,
            'listing_kind' => $kind,
            'property_subtype' => 'commercial',
            'title' => $title,
            'description' => $this->description($title, $kind),
            'price' => $price,
            'bedrooms' => null,
            'bathrooms' => 2,
            'built_area_sqft' => 4200,
            'floors' => 3,
            'furnishing_status' => 'unfurnished',
            'parking_available' => true,
        ];
    }

    protected function rental(
        string $userEmail,
        string $subtype,
        string $title,
        float $price,
        int $bedrooms,
        int $bathrooms,
        int $sqft,
        bool $shortTerm,
        bool $billsIncluded
    ): array {
        return array_merge(
            $this->residential($userEmail, 'rental', $subtype, $title, $price, $bedrooms, $bathrooms, $sqft),
            [
                'advance_payment_months' => 2,
                'deposit_months' => 2,
                'short_term_available' => $shortTerm,
                'bills_included' => $billsIncluded,
                'furnishing_status' => $shortTerm ? 'furnished' : 'semi_furnished',
            ]
        );
    }

    protected function wanted(string $userEmail, string $subtype, string $title, string $description): array
    {
        return [
            'user_email' => $userEmail,
            'listing_kind' => 'wanted',
            'property_subtype' => $subtype,
            'title' => $title,
            'description' => $description,
            'price' => null,
            'bedrooms' => null,
            'bathrooms' => null,
            'built_area_sqft' => null,
            'latitude' => null,
            'longitude' => null,
            'area' => null,
        ];
    }

    protected function description(string $title, string $kind): string
    {
        $kindLabel = config('listing.kinds.'.$kind.'.label', $kind);

        return $title.'. Listed as '.$kindLabel.' on Lanka Realtors. '
            .'Contact the poster for viewings. All details are sample seed data for development and demos.';
    }
}
