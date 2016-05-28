<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->command->info('Begin seeding system variables...');
        $this->call(CountriesSeeder::class);
        $this->command->info('Seeded countries!');
        $this->call(PermissionsTableSeeder::class);
        $this->command->info('Seeded Permissions!');
        $this->call(PropertiesTriggersTableSeeder::class);
        $this->command->info('Seeded Rule Properties & Triggers');

        $this->command->info('...Seeding dev data');
        $this->call(PusakaSetupSeeder::class);
        $this->command->info('Seeded Dev: Company, Project, User');

        $this->command->info('...done seeding!');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();

    }
}
