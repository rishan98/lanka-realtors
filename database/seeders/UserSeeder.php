<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public const DEMO_PASSWORD = 'password';

    public function run()
    {
        $password = Hash::make(self::DEMO_PASSWORD);

        User::updateOrCreate(
            ['email' => 'admin@lankarealtors.test'],
            [
                'name' => 'Site Admin',
                'password' => $password,
                'role' => User::ROLE_ADMIN,
                'approval_status' => User::APPROVAL_APPROVED,
                'phone' => '+94 11 234 5678',
                'email_verified_at' => now(),
            ]
        );

        $agents = [
            [
                'email' => 'nipun.perera@seed.test',
                'name' => 'Nipun Perera',
                'agency_name' => 'Colombo Prime Realty',
                'phone' => '+94 77 123 4567',
                'bio' => 'Residential sales specialist across Colombo 03–07 with 12+ years in the market.',
                'operating_since_year' => 2012,
                'buyers_served_estimate' => 340,
                'is_preferred' => true,
            ],
            [
                'email' => 'sanduni.fernando@seed.test',
                'name' => 'Sanduni Fernando',
                'agency_name' => 'Lanka Homes',
                'phone' => '+94 77 234 5678',
                'bio' => 'Apartments and luxury rentals in Colombo and Mount Lavinia.',
                'operating_since_year' => 2015,
                'buyers_served_estimate' => 210,
                'is_preferred' => true,
            ],
            [
                'email' => 'ravi.silva@seed.test',
                'name' => 'Ravi Silva',
                'agency_name' => 'Southern Coast Properties',
                'phone' => '+94 77 345 6789',
                'bio' => 'Galle, Matara, and coastal investment plots and villas.',
                'operating_since_year' => 2010,
                'buyers_served_estimate' => 185,
                'is_preferred' => true,
            ],
            [
                'email' => 'anuki.jayawardena@seed.test',
                'name' => 'Anuki Jayawardena',
                'agency_name' => 'Hill City Estates',
                'phone' => '+94 77 456 7890',
                'bio' => 'Kandy and central province bungalows, land, and holiday homes.',
                'operating_since_year' => 2016,
                'buyers_served_estimate' => 95,
                'is_preferred' => false,
            ],
            [
                'email' => 'dilan.rathnayake@seed.test',
                'name' => 'Dilan Rathnayake',
                'agency_name' => 'Metro Commercial',
                'phone' => '+94 77 567 8901',
                'bio' => 'Office spaces, retail units, and warehouses in Greater Colombo.',
                'operating_since_year' => 2014,
                'buyers_served_estimate' => 120,
                'is_preferred' => false,
            ],
            [
                'email' => 'tharushi.wickramasinghe@seed.test',
                'name' => 'Tharushi Wickramasinghe',
                'agency_name' => 'Independent',
                'phone' => '+94 77 678 9012',
                'bio' => 'First-time buyer guidance and affordable housing in Negombo and Ja-Ela.',
                'operating_since_year' => 2019,
                'buyers_served_estimate' => 68,
                'is_preferred' => false,
            ],
        ];

        foreach ($agents as $agent) {
            $preferred = $agent['is_preferred'];
            unset($agent['is_preferred']);

            $user = User::updateOrCreate(
                ['email' => $agent['email']],
                array_merge($agent, [
                    'password' => $password,
                    'role' => User::ROLE_AGENT,
                    'approval_status' => User::APPROVAL_APPROVED,
                    'email_verified_at' => now(),
                ])
            );

            $user->is_preferred = $preferred;
            $user->save();
        }

        $owners = [
            [
                'email' => 'owner.kamal@seed.test',
                'name' => 'Kamal Dias',
                'phone' => '+94 71 111 2222',
            ],
            [
                'email' => 'owner.malini@seed.test',
                'name' => 'Malini Gunasekara',
                'phone' => '+94 71 333 4444',
            ],
            [
                'email' => 'owner.raj@seed.test',
                'name' => 'Raj Mendis',
                'phone' => '+94 71 555 6666',
            ],
        ];

        foreach ($owners as $owner) {
            User::updateOrCreate(
                ['email' => $owner['email']],
                array_merge($owner, [
                    'password' => $password,
                    'role' => User::ROLE_OWNER,
                    'approval_status' => User::APPROVAL_APPROVED,
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
