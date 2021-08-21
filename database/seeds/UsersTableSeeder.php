<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Admin",
            "username"=> "admin",
            "email"=> "admin@test.com",
            "password"=> bcrypt("password"),
            "role"=> "purchasing",
        ]);
    }
}
