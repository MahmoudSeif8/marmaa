<?php

use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypesTableDataSeeder extends Seeder
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
                    DocumentType::create([
                        'name' => 'Experience',
                    ]);
                    break;
                case 2:
                    DocumentType::create([
                        'name' => 'Certificate',
                    ]);
                    break;
                default:
                    $i = 4;
            }
        }
    }
}
