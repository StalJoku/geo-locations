<?php

use Illuminate\Database\Seeder;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Device::class, 100000)->create()->each(function($device){

        	$boolean = rand(0, 1);
        	$ids = range(1, 20);
        	shuffle($ids);

        	if($boolean){
        		$sliced = array_slice($ids, 0, 2);
        		$device->users()->attach($sliced);
        	}
        });
    }
}
