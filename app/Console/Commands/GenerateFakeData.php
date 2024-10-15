<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\FakeSeeder;

class GenerateFakeData extends Command
{
    protected $signature = 'app:generate-fake-data';

    protected $description = 'For generating fake data for the application.';


    public function handle(): int
    {
        $this->call('db:seed', [
            '--class' => FakeSeeder::class,
        ]);

        return 0;
    }
}
