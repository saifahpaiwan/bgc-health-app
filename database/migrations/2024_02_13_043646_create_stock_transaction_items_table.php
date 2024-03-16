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
        Schema::create('stock_transaction_items', function (Blueprint $table) {
            $table->id(); 
            $table->integer('transaction_id')->nullable()->comment("รหัส transactions ID")->index();  
            $table->integer('product_id')->nullable()->comment('(Foreign Key): รหัสระบุสินค้าที่มีการเปลี่ยนแปลง')->index();  
            $table->integer('product_items_id')->nullable()->comment('(Foreign Key): รหัสระบุไอเทมสินค้าที่มีการเปลี่ยนแปลง')->index();  
            
            $table->integer('quantity_changed')->default(0)->comment("จำนวนที่มีการเปลี่ยนแปลง");
            $table->integer('unit_position')->nullable()->comment('ตำแหน่งของหน่วย'); 
            $table->integer('unit_id')->nullable()->comment('(Foreign Key): รหัสระบุหน่วยสินค้า|หน่วย (เช่น กล่อง, กิโลกรัม, มิลลิกรัม)')->index(); 
            $table->timestamp('expires_at')->nullable()->comment("วันหมดอายุของ (Products) ที่นำเข้า"); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transaction_items');
    }
};
