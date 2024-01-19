<?php

include("../vendor/autoload.php");

use Faker\Factory as Faker;
use Libs\Database\MySQL;
use Libs\Database\UsersTable;

//creating obj using the Faker static class
$faker = Faker::create();

//creating  UsersTable obj, in the user table parameter creating MySQL Obj
//first MySQL constructor works first become a obj for usertable
//second user table constructor work and connect the database
$table = new UsersTable(new MySQL());

if ($table) {
    echo "Database connection opened.\n";
    for ($i = 0; $i < 10; $i++) {
        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'password' => md5('password'),
            'role_id' => $i < 5 ? rand(1, 3) : 1
        ];
        $table->insert($data);
    }
    echo "Done populating users table.\n";
}
