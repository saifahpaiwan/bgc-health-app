<?php

namespace Database\Seeders;
  
use App\Models\units;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ["unit_name" => "PC", "description" => "[Piece] ชิ้นส่วนหรือชิ้น"],  
            ["unit_name" => "BT", "description" => "[Bottle] ขวด"],
            ["unit_name" => "BOX", "description" => "[Box] กล่อง"],
            ["unit_name" => "PAC", "description" => "[Packet] หีบห่อหรือบรรจุ"],
            ["unit_name" => "TAB", "description" => "[Tablet] เม็ดยา"],
            ["unit_name" => "TU", "description" => "[Tube] หลอด"], 
            ["unit_name" => "CAP", "description" => "[Capsule] แคปซูล"],  
            ["unit_name" => "RL", "description" => "[Roll] ม้วน"], 
            ["unit_name" => "TK", "description" => "[Tack] ถัง"], 
            ["unit_name" => "KG", "description" => "[Kilogram] กิโลกรัม"],  
            ["unit_name" => "MG", "description" => "[Milligram] มิลลิกรัม"],  
            ["unit_name" => "ML", "description" => "[Milliliter] มิลลิลิตร"],  
            ["unit_name" => "mcg", "description" => "[Microgram] ไมโครกรัม"],  
            ["unit_name" => "ซอง", "description" => "ซอง"],  
            ["unit_name" => "ตลับ", "description" => "ตลับ"],    
            ["unit_name" => "RI", "description" => "[Roll-In] ม้วนเข้า"], 
        ];

        foreach ($data as $row) {
            units::create(["unit_name" => $row['unit_name'], "description" => $row['description']]);
        }
    }
}
