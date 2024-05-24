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
        Schema::create('rcv_pos', function (Blueprint $table) {
            $table->id();
            $table->string('company_code', 20);
            $table->string('trans_type');
            $table->date('trans_date');
            $table->string('po_no', 100);
            $table->string('do_no', 100);
            $table->string('location', 100)->comment('diisi dengan plant_code');
            $table->string('warehouse', 100)->comment('diisi dengan sloc_code');
            $table->string('transportir', 100);
            $table->string('material_code');
            $table->string('uom')->comment('diisi dengan uom_code');
            $table->decimal('qty', 12, 2);
            $table->string('reference')->nullable();
            $table->string('posting_no')->nullable()->comment('diisi ketika sudah posting');
            $table->string('error_status')->nullable();
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
        Schema::dropIfExists('rcv_pos');
    }
};
