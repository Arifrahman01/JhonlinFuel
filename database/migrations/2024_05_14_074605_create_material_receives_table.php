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
        Schema::create('receipt_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('company_id');
            $table->string('receipt_no', 100);
            $table->string('po_no', 100);
            $table->string('erp_receipt_no', 100);
            $table->string('spb_no', 100);
            $table->string('vendor', 100);
            $table->tinyInteger('buyer_company_id');
            $table->tinyInteger('payer_company_id');
            $table->tinyInteger('period_id');
            $table->string('transportir', 100);
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receipt_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('header_id');
            $table->smallInteger('company_id');
            $table->integer('material_id');
            $table->string('material_code', 100);
            $table->string('part_no', 100)->nullable();
            $table->string('material_mnemonic', 255)->nullable();
            $table->string('material_description', 255);
            $table->decimal('qty');
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receipt_transfer_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('company_id');
            $table->integer('transfer_id');
            $table->string('receipt_transfer_no', 100);
            $table->smallInteger('from_company_id');
            $table->integer('from_plant_id');
            $table->integer('from_sloc_id');
            $table->smallInteger('to_company_id');
            $table->integer('to_plant_id');
            $table->integer('to_sloc_id');
            $table->integer('equipment_id');
            $table->string('equipment_driver', 50);
            $table->longText('notes')->nullable();
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receipt_transfer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('header_id');
            $table->smallInteger('company_id');
            $table->integer('material_id');
            $table->string('material_code', 100);
            $table->string('part_no', 100)->nullable();
            $table->string('material_mnemonic', 255)->nullable();
            $table->string('material_description', 255);
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
        Schema::dropIfExists('receipt_headers');
        Schema::dropIfExists('receipt_details');
        Schema::dropIfExists('receipt_transfer_headers');
        Schema::dropIfExists('receipt_transfer_details');
    }
};
