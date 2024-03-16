<?php

namespace Database\Seeders;

use App\Models\categcts; 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategctsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ["category_name" => "ยาต้านปวด (Painkillers)", "description" => "ยาต้านปวด: เช่น อะสไปริน, พาราเซตามอล, อิบุโพรเฟน, นัลโกเซติน ฯลฯ"], 
            ["category_name" => "ยาลดไข้ (Antipyretics)", "description" => "ยาลดไข้: เช่น พาราเซตามอล, อินดอล, แอสไพริน, พาราคีแมน ฯลฯ"],
            ["category_name" => "ยาต้านเยื่อหุ้มสมอง (Antidepressants)", "description" => "ยาต้านเยื่อหุ้มสมอง: เช่น พาโรเซแตมอล, กาบาเพนทิน, ไอบุโพรเฟน ฯลฯ"],
            ["category_name" => "ยาต้านชัก (Anticonvulsants)", "description" => "ยาต้านชัก: เช่น แฟนโตมิน, คาร์บามาซีปีน, วาลโปริเทต, ลามอทริปทัน ฯลฯ"],
            ["category_name" => "ยาต้านแพ้ (Antihistamines)", "description" => "ยาต้านแพ้: เช่น อันติฮิสตามีน, ลอราทาดีน, เซติริซีน ฯลฯ"],
            ["category_name" => "ยาแก้ไอและน้ำมูก (Cough and Cold Medications)", "description" => "ยาแก้ไอและน้ำมูก: เช่น ปาราเซตามอล, นัมิโพซีน, ซาลบูทามอล ฯลฯ"],
            ["category_name" => "ยาปฏิชีวนะ (Antibiotics)", "description" => "ยาปฏิชีวนะ: เช่น พามิโดริก, อัมพิซิลลิน, ไอโซนิอาซิด ฯลฯ"],
            ["category_name" => "ยาป้องกันโรค (Vaccines)", "description" => "ยาป้องกันโรค: เช่น ยาอนาเซน, ยาเม็ดคุมกำเนิด, วิตามิน ฯลฯ"],
            ["category_name" => "ยาฆ่าเชื้อ (Antiseptics and Disinfectants)", "description" => "ยาฆ่าเชื้อ: เช่น พาราเซตามอล, อะม็อกซาไซลลิน, ซัลไฟซิลลิน ฯลฯ"],
            ["category_name" => "ยาใช้ภายนอก (Topical Medications)", "description" => "ยาใช้ภายนอก: เช่น ยาทาผิว, ยานวด, ยาสำหรับขับถ่าย ฯลฯ"],
            ["category_name" => "ยาใช้ภายใน (Internal Medications)", "description" => "ยาใช้ภายใน: เช่น ยาเม็ด, ยาแคปซูล, ยาน้ำ, ยาฉีด ฯลฯ"],
            ["category_name" => "ยาทางเดินอาหาร (Gastrointestinal Medications)", "description" => "ยาทางเดินอาหาร: เช่น ยาลดกรด, ยาแก้ปวดท้อง, ยาสำหรับโรคกระเพาะ ฯลฯ"],
            ["category_name" => "ยาทาเภสัชกรรม (Pharmaceutical Preparations)", "description" => "ยาทาเภสัชกรรม: เช่น ยาลดรอยแดง, ยาลดบวม ฯลฯ"],
            ["category_name" => "ยาฆ่าเชื้อและเชื้อโรค (Antimicrobials and Antifungals)", "description" => "ยาฆ่าเชื้อและเชื้อโรค: เช่น ยาฆ่าเชื้อบริเวณที่บาดแผล, ยาฆ่าเชื้อมือ, ยาฆ่าเชื้อในร่างกาย ฯลฯ"],
            ["category_name" => "อื่นๆ", "description" => ""],
        ];

        foreach ($data as $row) {
            categcts::create(["category_name" => $row['category_name'], "description" => $row['description']]);
        }
    }
}
