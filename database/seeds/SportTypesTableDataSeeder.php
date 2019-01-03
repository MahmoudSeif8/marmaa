<?php

use Illuminate\Database\Seeder;
use App\Models\SportType;

class SportTypesTableDataSeeder extends Seeder
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
                    SportType::create([
                        'name' => 'Soccer',
                    ]);
                    break;
                case 2:
                    SportType::create([
                        'name' => 'Volley Ball',
                    ]);
                    break;
                case 3:
                    SportType::create([
                        'name' => 'Hand Ball',
                    ]);
                    break;
                case 4:
                    SportType::create([
                        'name' => 'Basket Ball',
                    ]);
                    break;
                case 5:
                    SportType::create([
                        'name' => 'Tennis',
                    ]);
                    break;
                default:
                    $i = 7;
            }
        }
    }
}
