<?php

use Illuminate\Database\Seeder;
use App\Models\FieldSize;

class FieldSizesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 8; $i++) {
            switch ($i) {
                case 1:
                    FieldSize::create([
                        'size' => '5x5',
                    ]);
                    break;
                case 2:
                    FieldSize::create([
                        'size' => '6x6',
                    ]);
                    break;
                case 3:
                    FieldSize::create([
                        'size' => '7x7',
                    ]);
                    break;
                case 4:
                    FieldSize::create([
                        'size' => '8x8',
                    ]);
                    break;
                case 5:
                    FieldSize::create([
                        'size' => '9x9',
                    ]);
                    break;
                case 6:
                    FieldSize::create([
                        'size' => '10x10',
                    ]);
                    break;
                case 7:
                    FieldSize::create([
                        'size' => '11x11',
                    ]);
                    break;
                default:
                    $i = 9;
            }
        }
    }
}
