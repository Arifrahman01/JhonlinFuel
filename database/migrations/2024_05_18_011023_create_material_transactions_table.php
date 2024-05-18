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
        Schema::create('material_transactions_tmp', function (Blueprint $table) {
            $table->id();
            $table->string('company_code', 10);
            $table->enum('trans_type', ['ISS', 'RCV', 'TRF']);
            $table->date('trans_date');
            $table->string('fuelman', 100);
            $table->string('equipment_no', 100);
            $table->string('location', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('activity', 100)->nullable();
            $table->string('fuel_type', 100);
            $table->decimal('qty');
            $table->string('statistic_type', 100)->nullable();
            $table->decimal('meter_value')->nullable();
            $table->integer('created_id');
            $table->integer('updated_id');
            $table->integer('deleted_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('material_transactions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('company_id', false, true);
            $table->string('posting_no', 50);
            $table->string('trans_type', 10);
            $table->date('trans_date');
            $table->smallInteger('fuelman_id');
            $table->string('fuelman_name');
            $table->smallInteger('equipment_id', false, true);
            $table->string('equipment_no', 100);
            $table->smallInteger('location_id', false, true);
            $table->string('location_name', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->smallInteger('activity_id', false, true)->nullable();
            $table->string('activity_name', 100)->nullable();
            $table->string('fuel_type', 100);
            $table->decimal('qty');
            $table->string('statistic_type', 100)->nullable();
            $table->decimal('meter_value')->nullable();
            $table->integer('created_id');
            $table->integer('updated_id');
            $table->integer('deleted_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_transactions_tmp');
        Schema::dropIfExists('material_transactions');
    }
};
