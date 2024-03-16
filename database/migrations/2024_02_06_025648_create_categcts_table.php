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
        Schema::create('categcts', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->nullable()->comment('ชื่อประเภทสินค้า');
            $table->text('description')->nullable()->comment('รายละเอียดประเภท'); 
            $table->string('color')->nullable()->comment('สีประเภทสินค้า');
            $table->char('status', 1)->default(1)->comment('0 = Disable | Enable = 1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categcts');
    }
};

