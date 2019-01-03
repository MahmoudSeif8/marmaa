<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentOption;

class PaymentOptionsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 3; $i++) {
            switch ($i) {
                case 1:
                    PaymentOption::create([
                        'name' => 'Online',
                    ]);
                    break;
                case 2:
                    PaymentOption::create([
                        'name' => 'Offline',
                    ]);
                    break;
                default:
                    $i = 4;
            }
        }
    }
}
