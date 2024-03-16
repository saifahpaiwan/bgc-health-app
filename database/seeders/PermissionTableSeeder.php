<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionTableSeeder extends Seeder
{ 
    public function run()
    { 
        $permissions = [
            ["group" => "user", "name" => "user-list", "description" => "หน้ารายการผู้ใช้งาน"],
            ["group" => "user", "name" => "user-create", "description" => "หน้าการสร้างผู้ใช้งาน"],
            ["group" => "user", "name" => "user-edit", "description" => "หน้าการแก้ไขผู้ใช้งาน"],
            ["group" => "user", "name" => "user-delete", "description" => "สำหรับลบผู้ใช้งาน"],
            ["group" => "user", "name" => "user-set-roles", "description" => "สำหรัผู้ใช้งานที่สามารถกำหนด Role ได้"],
              
            ["group" => "role", "name" => "role-list", "description" => "หน้ารายการข้อมูลสิทธ์การใช้งาน (Roles)"],
            ["group" => "role", "name" => "role-create", "description" => "หน้าการสร้างสิทธ์การใช้งาน (Roles)"],
            ["group" => "role", "name" => "role-edit", "description" => "หน้าการแก้ไขสิทธ์การใช้งาน (Roles)"],
            ["group" => "role", "name" => "role-delete", "description" => "สำหรับลบสิทธ์การใช้งาน (Roles)"],

            ["group" => "dispensing-medicine", "name" => "dispensing-medicine-list", "description" => "หน้าการสร้างข้อมูลการจ่ายยา (Dispensing Medicine)"],
            ["group" => "dispensing-medicine", "name" => "dispensing-medicine-create", "description" => "หน้าการสร้างข้อมูลการจ่ายยา (Dispensing Medicine)"],
            ["group" => "dispensing-medicine", "name" => "dispensing-medicine-edit", "description" => "หน้าการแก้ไขข้อมูลการจ่ายยา (Dispensing Medicine)"],
            ["group" => "dispensing-medicine", "name" => "dispensing-medicine-delete", "description" => "สำหรับลบข้อมูลการจ่ายยา (Dispensing Medicine)"],

            
        ];
 
        foreach ($permissions as $row) {
            Permission::create(["group"=> $row['group'], "name" => $row['name'], "description" => $row['description']]);
        }
    }
}