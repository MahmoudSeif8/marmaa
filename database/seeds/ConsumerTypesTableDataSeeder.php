<?php

use Illuminate\Database\Seeder;
use App\Models\ConsumerType;

class ConsumerTypesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 6; $i++) {
            switch ($i) {
                case 1:
                    ConsumerType::create([
                        'name' => 'Player',
                    ]);
                    break;
                case 2:
                    ConsumerType::create([
                        'name' => 'Referee',
                    ]);
                    break;
                case 3:
                    ConsumerType::create([
                        'name' => 'Photographer',
                    ]);
                    break;
                case 4:
                    ConsumerType::create([
                        'name' => 'Commentator',
                    ]);
                    break;
                case 5:
                    ConsumerType::create([
                        'name' => 'Broker',
                    ]);
                    break;
                default:
                    $i = 7;
            }
        }
    }
}
