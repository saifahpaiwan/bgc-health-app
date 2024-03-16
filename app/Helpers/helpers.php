<?php

// #### หาวันที่ระหว่างวันที่เริ่มถึงสิ้นสุด #### //

use App\Models\employees_plant;
use App\Models\Notifications;
use App\Models\plants;
use App\Models\product_items;
use App\Models\product_stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (!function_exists('helperDateDiff')) {
    function helperDateDiff($start_date, $end_date)
    {
        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);
        $interval = $start_date->diff($end_date);
        return $interval->format('%a วัน %H ชม. %i นาที');
    }
}

// #### แสดงสถานะด้วยรูปแบบ HTML #### //
if (!function_exists('helperStatusShow')) {
    function helperStatusShow($status)
    {
        $data = [
            0 => '<span class="badge badge-secondary p-1">Disable</span>',
            1 => '<span class="badge badge-success p-1">Enable</span>'
        ];

        return (isset($data[$status])) ? $data[$status] : null;
    }
}

// #### ข้อมูล Plants  #### //
if (!function_exists('helperPlants')) {
    function helperPlants()
    {
        $Query = product_items::select('plant_id')->where('status', 1)
        ->groupBy('plant_id')
        ->get();
        
        $items = [];
        if(isset($Query) && count($Query) > 0){
            foreach($Query as $row){
                $items[$row->plant_id]['id'] = $row->plant_id;
                $items[$row->plant_id]['plant'] = $row->rPlant?->plant;
            }
        }
 
        return $items; 
    }
}

// #### ข้อมูล Plants Search  #### //
if (!function_exists('helperPlantFind')) {
    function helperPlantFind($id = null)
    {
        if (!empty($id)) {
            return plants::where('id', $id)->first()->plant;
        }
    }
}
 
// #### Check Plant #### //
if (!function_exists('helperCheckPlant')) {
    function helperCheckPlant()
    {
        $plantID = null;
        if (isset(auth()->user()->plant_id) && !empty(auth()->user()->plant_id)) {
            $plantID = auth()->user()->plant_id;
        } else if (session('setSeletePlant') && !empty(session('setSeletePlant'))) {
            $plantID = session('setSeletePlant');
        }
        return $plantID;
    }
} 
