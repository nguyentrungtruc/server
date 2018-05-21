<?php

use Illuminate\Database\Seeder;

class ActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ec_activities')->insert([
			'daysofweek' =>'Thứ hai',
			'number'     => '1'
    	]);

    	DB::table('ec_activities')->insert([
			'daysofweek' =>'Thứ ba',
			'number'     => '2'
    	]);

    	DB::table('ec_activities')->insert([
			'daysofweek' =>'Thứ tư',
			'number'     => '3'
    	]);

    	DB::table('ec_activities')->insert([
			'daysofweek' =>'Thứ năm',
			'number'     => '4'
    	]);

    	DB::table('ec_activities')->insert([
			'daysofweek' =>'Thứ sáu',
			'number'     => '5'
    	]);

    	DB::table('ec_activities')->insert([
			'daysofweek' =>'Thứ bảy',
			'number'     => '6'
    	]);

    	DB::table('ec_activities')->insert([
			'daysofweek' =>'Chủ nhật',
			'number'     => '0'
    	]);
    
    }
}
