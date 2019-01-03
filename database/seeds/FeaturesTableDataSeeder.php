<?php

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeaturesTableDataSeeder extends Seeder
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
                    Feature::create([
                        'name' => 'WC',
                        'image' => '1545746860.png',
                    ]);
                    break;
                case 2:
                    Feature::create([
                        'name' => 'Shower room',
                        'image' => '1545746655.png',
                    ]);
                    break;
                case 3:
                    Feature::create([
                        'name' => 'Buffet',
                        'image' => '1545746847.png',
                    ]);
                    break;
                default:
                    $i = 5;
            }
        }
    }
}
