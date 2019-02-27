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
        $user = new User();
        $user->name = 'admin';
        $user->email = 'login@mainemail.com';
        $user->password = bcrypt("admin");
        $user->type = 'admin';
        $user->remember_token = Str::random(10);
        $user->save();
        factory(App\User::class, 20)->create();
    }
}
