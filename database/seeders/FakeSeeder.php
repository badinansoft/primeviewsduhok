<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Customer;
use App\Models\Gas;
use App\Models\Level;
use App\Models\Service;
use App\Models\Tower;
use Database\Factories\LevelFactory;
use Database\Factories\TowerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        echo "Generating fake data...\n";
        echo "--------------------------------\n";
        echo "Generating 100 towers...\n";
        Tower::query()->truncate();
        TowerFactory::new()->count(100)->create();
        echo "--------------------------------\n";
        echo "Generating 100 levels...\n";
        Level::query()->truncate();
        LevelFactory::new()->count(100)->create();
        echo "--------------------------------\n";
        echo "Generating 100 customers...\n";
        Customer::query()->truncate();
        Customer::factory()->count(100)->create();
        echo "--------------------------------\n";
        echo "Generating 100 apartments...\n";
        Apartment::query()->truncate();
        Apartment::factory()->count(100)->create();
        echo "--------------------------------\n";
        echo "Generating 1000 services...\n";
        Service::query()->truncate();
        Service::factory()->count(1000)->create();
        echo "--------------------------------\n";
        Gas::query()->truncate();
        Gas::factory()->count(1000)->create();
        echo "Generating 1000 gas services...\n";
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        echo "Fake data generated successfully.\n";
    }
}
