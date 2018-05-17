<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$role_admin = Role::where('name', 'Admin')->first();

    	$admin = new User();
    	$admin->name = 'Nguyễn Trung Trực';
    	$admin->email = 'dofuu.company@gmail.com';
    	$admin->password = bcrypt('B1mKj557501Y9');
    	$admin->birthday = '1993-07-12';
    	$admin->gender = 1;
    	$admin->address = '160/18 đường 30/4 Phường An Phú, Quận Ninh Kiều, TP Cần Thơ';
    	$admin->phone = '0932903406';
    	$admin->actived = 1;
    	$admin->role_id = $role_admin->id;
    	$admin->save();
    }
}
