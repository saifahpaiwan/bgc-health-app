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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name')->nullable()->comment('ชื่อหน่วย (เช่น กล่อง, กิโลกรัม, มิลลิกรัม, เม็ด, มิลลิกรัม, ไมโครกรัม)');   
            $table->text('description')->nullable()->comment('รายละเอียดหน่วย');   
            $table->char('status', 1)->default(1)->comment('0 = Disable | Enable = 1'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
