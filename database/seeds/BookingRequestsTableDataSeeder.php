<?php

use Illuminate\Database\Seeder;
use App\Models\BookingRequest;

class BookingRequestsTableDataSeeder extends Seeder
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
                    BookingRequest::create([
                        'name' => 'Auto',
                    ]);
                    break;
                case 2:
                    BookingRequest::create([
                        'name' => 'After Permission',
                    ]);
                    break;
                default:
                    $i = 4;
            }
        }
    }
}
