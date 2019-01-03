<?php

use Illuminate\Database\Seeder;
use App\Models\PlaygroundType;

class PlaygroundTypesTableDataSeeder extends Seeder
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
                    PlaygroundType::create([
                        'name' => 'Natural grass',
                    ]);
                    break;
                case 2:
                    PlaygroundType::create([
                        'name' => 'Manufacture grass',
                    ]);
                    break;
                case 3:
                    PlaygroundType::create([
                        'name' => 'Sand',
                    ]);
                    break;
                case 4:
                    PlaygroundType::create([
                        'name' => 'Rubber',
                    ]);
                    break;
                case 5:
                    PlaygroundType::create([
                        'name' => 'Closed hall',
                    ]);
                    break;
                default:
                    $i = 7;
            }
        }
    }
}
