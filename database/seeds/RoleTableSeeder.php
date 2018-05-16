<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin->description = 'Manage in company';
        $role_admin->save();


        $role_employee = new Role();
        $role_employee->name = 'Employee';
        $role_employee->description = 'A worker in company';
        $role_employee->save();
        
     	$role_shipper = new Role();
     	$role_shipper->name = 'Shipper';
     	$role_shipper->description = 'A Shipper in company';
     	$role_shipper->save();

     	$role_partner = new Role();
     	$role_partner->name = 'Partner';
     	$role_partner->description = 'A partner with company';
     	$role_partner->save();


        $role_customer = new Role();
        $role_customer->name = 'Customer';
        $role_customer->description = 'A customer in website';
        $role_customer->save();
    }
}
