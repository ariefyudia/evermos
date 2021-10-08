<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTable();

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Food($faker));

        foreach (range(1, 50) as $index)  {
            DB::table('products')->insert([
                'id' => Str::uuid(),
                'name' => $faker->ingredient,
                'price' => $faker->numberBetween($min = 500, $max = 10000),
                'stock' => $faker->numberBetween($min = 3, $max = 10),
                'description'=> $faker->paragraph($nb =8),
                'start_sale' => date('Y-m-d H:i:s'),
                'end_sale' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day'))
            ]);
        }
    }

    public function truncateTable()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('products')->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
