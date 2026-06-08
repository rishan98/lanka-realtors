<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CitySeeder::class,
            ListingSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('Demo accounts (password: '.UserSeeder::DEMO_PASSWORD.')');
        $this->command->table(
            ['Role', 'Email'],
            [
                ['Admin', 'admin@lankarealtors.test'],
                ['Agent', 'nipun.perera@seed.test'],
                ['Agent', 'sanduni.fernando@seed.test'],
                ['Owner', 'owner.kamal@seed.test'],
            ]
        );
    }
}
