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
        Schema::create('material_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('company_id');
            $table->integer('doc_header_id');
            $table->string('doc_no', 100);
            $table->integer('doc_detail_id');
            $table->integer('material_id');
            $table->string('material_code', 100);
            $table->string('part_no', 100)->nullable();
            $table->string('material_mnemonic', 255)->nullable();
            $table->string('material_description', 255);
            $table->date('movement_date');
            $table->enum('movement_type', ['receipt', 'transfer', 'receipt-transfer', 'issue', 'adjust'])->default('receipt');
            $table->smallInteger('plant_id');
            $table->smallInteger('sloc_id');
            $table->decimal('qty');
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
        Schema::dropIfExists('material_movements');
    }
};
