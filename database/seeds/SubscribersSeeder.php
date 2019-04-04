<?php

use Illuminate\Database\Seeder;
use App\Subscriber;

class SubscribersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        // $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        foreach (range(1, 2000) as $index) {
            Subscriber::create([
                'user_id' => $faker->numberBetween($min = 1, $max = 10),
                'email' => $faker->email,
                'name' => $faker->name,
            ]);
        }
    }
}
