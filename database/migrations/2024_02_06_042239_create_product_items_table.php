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
        Schema::create('product_items', function (Blueprint $table) {
            $table->id(); 
            $table->integer('product_id')->nullable()->comment('(Foreign Key): รหัสระบุสินค้า')->index(); 
            $table->integer('plant_id')->nullable()->comment("รหัส Plant")->index(); 
            $table->integer('minimum_quantity')->nullable()->comment('ปริมาณขั้นต่ำ (Safety Stock)'); 
            $table->integer('minimum_unitID')->nullable()->comment('(Foreign Key): รหัสระบุหน่วยสินค้า|หน่วย (เช่น กล่อง, กิโลกรัม, มิลลิกรัม)')->index(); 
            $table->integer('unit_in_stock')->default(0)->comment("จำนวนคงเหลือ (STOCK)");    
            $table->char('status', 1)->default(1)->comment('0 = Disable | Enable = 1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};
