<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Room;

class RoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        foreach(range(0,2) as $i){
            $input['room_name'] = $faker->name;
            $input['room_capacity'] = random_int(100, 999);
            $input['photo'] = $faker->imageUrl;
            $user = Room::create($input);
        }
    }
}
