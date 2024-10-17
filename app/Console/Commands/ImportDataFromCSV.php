<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\Customer;
use App\Models\Gas;
use App\Models\Level;
use App\Models\Service;
use App\Models\Tower;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDataFromCSV extends Command
{
    protected $signature = 'app:import-data-from-c-s-v';

    protected $description = 'Import data from CSV file.';

    public function handle(): void
    {
        // read file information.csv from storage/app folder
        $file = fopen(storage_path('data/information.csv'), 'r');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Tower::query()->truncate();
        Level::query()->truncate();
        Customer::query()->truncate();
        Apartment::query()->truncate();
        Service::query()->truncate();
        Gas::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // read the first row of the file
        $header = fgetcsv($file);

        // read the rest of the file
        while ($row = fgetcsv($file)) {
            // first row is tower check if is exisits just get id
            $tower = Tower::query()->firstOrCreate(['name' => $row[0]]);
            // second is level check if is exists just get id
            $level = Level::query()->firstOrCreate(['name' => $row[1]]);
            // thered check if apartment exists
            $apartment = $tower->apartments()->firstOrCreate([
                'number' => $row[2],
                'level_id' => $level->id,
                'view' => str_replace(' ', '', $row[3]),
                'area' => $row[4],
            ]);

            // forth and fifth is customer name and phone
            // check first customer name already exists
            $customer = Customer::query()->where('name', $row[5])->first();
            if (!$customer) {
                $customer = new Customer();
                $customer->name = $row[5];
                $customer->phone = $row[6];
                $customer->save();
            }

            // attach customer to apartment
            $apartment->customer_id = $customer->id;
            $apartment->save();

            echo "Apartment {$apartment->title} created successfully.\n";
        }
    }
}
