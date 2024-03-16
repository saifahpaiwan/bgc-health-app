<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();  
            $table->string('product_code')->nullable()->comment('รหัสสินค้า')->index();
            $table->string('product_name')->nullable()->comment('ชื่อสินค้า');
            $table->string('product_images')->nullable()->comment('รูปสินค้า');
            $table->text('description')->nullable()->comment('รายละเอียดเกี่ยวกับสินค้า');   
            $table->integer('category_id')->nullable()->comment('(Foreign Key): รหัสระบุประเภทสินค้า')->index();    
            $table->float('default_price', 10,2)->nullable()->comment("ราคาค่าเริ่มต้นต่อหน่วย"); 

            $table->integer('quantity_per_unit')->default(0)->comment("จำนวนต่อหน่วย เช่น 1:10, 1:1");
            $table->integer('unit_id')->nullable()->comment('(Foreign Key): รหัสระบุหน่วยสินค้า|หน่วย (เช่น กล่อง, กิโลกรัม, มิลลิกรัม)')->index(); 
            $table->integer('quantity_per_subunit')->default(0)->comment("จำนวนต่อหน่วยย่อย เช่น 1:10, 1:1"); 
            $table->integer('subunit_id')->nullable()->comment('(Foreign Key): รหัสระบุหน่วยย่อย|หน่วยย่อย (เช่น เม็ด, มิลลิกรัม, ไมโครกรัม)')->index(); 
             
            $table->integer('weight')->default(0)->comment("ปริมาณ/น้ำหนัก");
            $table->integer('weight_unitID')->nullable()->comment('(Foreign Key): รหัสระบุหน่วยสินค้า|หน่วย (เช่น กล่อง, กิโลกรัม, มิลลิกรัม)')->index();
            
            $table->char('status', 1)->default(1)->comment('0 = Disable | Enable = 1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
