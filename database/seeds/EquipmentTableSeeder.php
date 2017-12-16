<?php

use Illuminate\Database\Seeder;
use App\Equipment;


class EquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Equipment::truncate();
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i ++) {
            Equipment::create([
                "brand" => $faker->word,
                "type" => $faker->word,
                "model" => $faker->word,
                "description" => $faker->paragraph
            ]);
        }
    }
}
