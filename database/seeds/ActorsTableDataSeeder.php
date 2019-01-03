<?php

use Illuminate\Database\Seeder;
use App\Models\Actor;

class ActorsTableDataSeeder extends Seeder
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
                    Actor::create([
                        'name' => 'Admin',
                    ]);
                    break;
                case 2:
                    Actor::create([
                        'name' => 'Consumer',
                    ]);
                    break;
                case 3:
                    Actor::create([
                        'name' => 'Field Owner',
                    ]);
                    break;
                case 4:
                    Actor::create([
                        'name' => 'Owner User',
                    ]);
                    break;
                default:
                    $i = 6;
            }
        }
    }
}
