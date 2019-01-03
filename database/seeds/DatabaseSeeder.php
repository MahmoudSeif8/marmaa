<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ActorsTableDataSeeder::class,
            BankCardTypesTableDataSeeder::class,
            BookingOptionsTableDataSeeder::class,
            BookingRequestsTableDataSeeder::class,
            ConsumerTypesTableDataSeeder::class,
            CostingOptionsTableDataSeeder::class,
            DocumentTypesTableDataSeeder::class,
            FeaturesTableDataSeeder::class,
            FieldSizesTableDataSeeder::class,
            PaymentOptionsTableDataSeeder::class,
            PlaygroundTypesTableDataSeeder::class,
            PositionCategoriesTableDataSeeder::class,
            SportTypesTableDataSeeder::class,
        ]);
    }
}
