<?php

use Illuminate\Database\Seeder;
use App\Models\PositionCategory;

class PositionCategoriesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 5; $i++) {
            switch ($i) {
                case 1:
                    PositionCategory::create([
                        'name' => 'Goalkeeper',
                    ]);
                    break;
                case 2:
                    PositionCategory::create([
                        'name' => 'Defender',
                    ]);
                    break;
                case 3:
                    PositionCategory::create([
                        'name' => 'Midfielder',
                    ]);
                    break;
                case 4:
                    PositionCategory::create([
                        'name' => 'Forward',
                    ]);
                    break;
                default:
                    $i = 6;
            }
        }
    }
}
