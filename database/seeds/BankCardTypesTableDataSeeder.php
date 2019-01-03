<?php

use Illuminate\Database\Seeder;
use App\Models\BankCardType;

class BankCardTypesTableDataSeeder extends Seeder
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
                    BankCardType::create([
                        'name' => 'VISA Card',
                    ]);
                    break;
                case 2:
                    BankCardType::create([
                        'name' => 'Master Card',
                    ]);
                    break;
                default:
                    $i = 4;
            }
        }
    }
}
