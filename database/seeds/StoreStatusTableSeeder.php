<?php

use Illuminate\Database\Seeder;
use App\Models\StoreStatus;
class StoreStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status_green = new StoreStatus();
        $status_green->store_status_name = 'Mở cửa';
        $status_green->store_status_description = 'Trạng thái mở cửa của cửa hàng';
        $status_green->color = 'green';
        $status_green->save();
        
    }
}
