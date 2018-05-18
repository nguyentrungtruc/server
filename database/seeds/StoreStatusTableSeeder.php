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
        $status_green->color = 'green accent-4';
        $status_green->save();

        $status_red = new StoreStatus();
        $status_red->store_status_name = 'Đóng cửa';
        $status_red->store_status_description = 'Trạng thái đóng cửa của cửa hàng';
        $status_red->color = 'red accent-4';
        $status_red->save();

        $status_yellow = new StoreStatus();
        $status_yellow->store_status_name = 'Tạm ngưng hoạt động';
        $status_yellow->store_status_description = 'Trạng thái tạm ngưng hoạt động của cửa hàng';
        $status_yellow->color = 'yellow accent-4';
        $status_yellow->save();

        $status_grey = new StoreStatus();
        $status_grey->store_status_name = 'Ngưng hoạt động';
        $status_grey->store_status_description = 'Trạng thái chấm dứt hoạt động của cửa hàng';
        $status_grey->color = 'grey';
        $status_grey->save();
        
    }
}
