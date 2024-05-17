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
        Schema::create('issue_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('company_id');
            $table->string('issue_no', 100);
            $table->string('department', 50);
            $table->smallInteger('activity_id');
            $table->string('fuelman', 50);
            $table->integer('equipment_id');
            $table->string('equipment_driver', 50);
            $table->longText('notes')->nullable();
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('issue_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('header_id');
            $table->smallInteger('company_id');
            $table->integer('material_id');
            $table->string('material_code', 100);
            $table->string('part_no', 100)->nullable();
            $table->string('material_mnemonic', 255)->nullable();
            $table->string('material_description', 255);
            $table->smallInteger('uom_id');
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
        Schema::dropIfExists('issue_headers');
        Schema::dropIfExists('issue_details');
    }
};
