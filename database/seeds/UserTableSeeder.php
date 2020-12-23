<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
Use App\Role;

class UserTableSeeder extends Seeder
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
        // foreach(range(0,2) as $i){
        //     DB::table('users')->insert([
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'password' => bcrypt('password'),
        //         'photo' => $faker->imageUrl,
        //     ]);
        // }
        $input['name'] = 'admin';
        $input['email'] = 'admin@email.com';
        $input['password'] = bcrypt('password');
        $input['role'] = 2;
        $input['photo'] = $faker->imageUrl;
        $user = User::create($input);
        $user->roles()->attach(Role::where('name', 'admin')->first());
    }
}
