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
        // Schema::create('periods', function (Blueprint $table) {
        //     $table->tinyIncrements('id');
        //     $table->date('period_start');
        //     $table->date('period_end');
        //     $table->longText('notes')->nullable();
        //     $table->integer('created_id')->nullable();
        //     $table->integer('updated_id')->nullable();
        //     $table->integer('deleted_id')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('periods');
    }
};
