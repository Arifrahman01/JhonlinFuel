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
        Schema::create('material_stocks', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('company_id');
            $table->smallInteger('plant_id');
            $table->smallInteger('sloc_id');
            $table->integer('material_id');
            $table->string('material_code', 100);
            $table->string('part_no', 100)->nullable();
            $table->string('material_mnemonic', 255)->nullable();
            $table->string('material_description', 255);
            $table->smallInteger('uom_id');
            $table->decimal('qty');
            $table->enum('status', ['on hand', 'intransit'])->default('on hand');
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_stocks');
    }
};
