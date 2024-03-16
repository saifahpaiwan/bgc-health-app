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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('plant_id')->nullable()->comment("รหัส Plant")->index();
            $table->dateTime('transaction_date')->nullable()->comment('วันที่ทรานแซคชันเกิดขึ้น');
            $table->char('transaction_type', 1)->default('I')->comment('ประเภททรานแซคชัน : I = Import | E = Export'); 
            $table->text('description')->nullable()->comment('รายละเอียดเกี่ยวกับทรานแซคชันเกิดขึ้น');   
            $table->integer('create_uid')->nullable()->comment('(Foreign Key): รหัสผู้ทำรายการการเปลี่ยนแปลง')->index();  
            $table->timestamps();
            
        });
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
