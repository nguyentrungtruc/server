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
    	$role_partner = Role::where('name', 'Partner')->first();
    	$role_employee = Role::where('name', 'Employee')->first();
    	$role_shipper  = Role::where('name', 'Shipper')->first();

    	$admin = new User();
    	$admin->name = 'Nguyễn Trung Trực';
    	$admin->email = 'admin@gmail.com';
    	$admin->password = bcrypt('admin');
    	$admin->birthday = '1993-07-12';
    	$admin->gender = 1;
    	$admin->address = '160/18 đường 30/4 Phường An Phú, Quận Ninh Kiều, TP Cần Thơ';
    	$admin->phone = '0932903406';
    	$admin->actived = 1;
    	$admin->role_id = $role_admin->id;
    	$admin->save();

    	$employee = new User();
    	$employee->name = 'Võ Thị Hồng Giang';
    	$employee->email = 'vothihonggiang@gmail.com';
    	$employee->password = bcrypt('12345678');
    	$employee->birthday = '1990-10-30';
    	$employee->gender = 0;
    	$employee->address = '160/20D đường Tầm Du Phường Hưng Lợi, Q.Ninh Kiều, TP.Cần Thơ';
    	$employee->phone = '01678361777';
    	$employee->actived = 1;
    	$employee->role_id = $role_employee->id;
    	$employee->save();
    	// $employee->roles()->attach($role_employee);

    	$partner = new User();
    	$partner->name = 'Trần Ngọc Điệp';
    	$partner->email = 'tranngocdiep@gmail.com';
    	$partner->password = bcrypt('12345678');
    	$partner->birthday = '1982-12-12';
    	$partner->gender = 0;
    	$partner->address = '110 Lê Lai, Quận Ninh Kiều, Thành Phố Cần Thơ';
    	$partner->phone = '0946363839';
    	$partner->actived = 0;
        $partner->role_id = $role_partner->id;
    	$partner->have_store = 1;
    	$partner->save();
    	// $partner->roles()->attach($role_partner);
  
    	// $user = factory(App\Models\User::class, 100)->create();
    }
}
