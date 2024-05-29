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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('company_code', 20);
            $table->string('warehouse', 100)->comment('diisi dengan sloc_code');
            $table->string('trans_type', 100);
            $table->date('trans_date');
            $table->string('fuelman', 100)->comment('diisi dengan fuelman NIK');;
            $table->string('equipment_no', 100);
            $table->string('location', 100)->comment('diisi dengan plant code');
            $table->string('department', 100)->comment('diisi dengan department code');
            $table->string('activity', 100)->comment('diisi dengan activity code');
            $table->string('material_code', 100)->comment('diisi dengan material code');
            $table->string('uom')->comment('diisi dengan uom_code');
            $table->decimal('qty', 12, 2);
            $table->string('statistic_type', 100)->comment('diisi dengan hm/km');
            $table->decimal('meter_value', 8, 2)->comment('diisi dengan nilai hm/km');
            $table->string('posting_no')->nullable();
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
        Schema::dropIfExists('issues');
    }
};
