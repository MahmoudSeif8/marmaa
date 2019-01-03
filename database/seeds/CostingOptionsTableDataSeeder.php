<?php

use Illuminate\Database\Seeder;
use App\Models\CostingOption;

class CostingOptionsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 4; $i++) {
            switch ($i) {
                case 1:
                    CostingOption::create([
                        'name' => 'Shared',
                    ]);
                    break;
                case 2:
                    CostingOption::create([
                        'name' => 'Covered',
                    ]);
                    break;
                case 3:
                    CostingOption::create([
                        'name' => 'Charged',
                    ]);
                    break;
                default:
                    $i = 5;
            }
        }
    }
}
