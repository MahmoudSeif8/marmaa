<?php

use Illuminate\Database\Seeder;
use App\Models\BookingOption;

class BookingOptionsTableDataSeeder extends Seeder
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
                    BookingOption::create([
                        'name' => 'Economy',
                    ]);
                    break;
                case 2:
                    BookingOption::create([
                        'name' => 'Semi-Flexible',
                    ]);
                    break;
                case 3:
                    BookingOption::create([
                        'name' => 'Flexible',
                    ]);
                    break;
                default:
                    $i = 5;
            }
        }
    }
}
