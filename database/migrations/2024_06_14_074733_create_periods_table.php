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
        Schema::create('periods', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('period_name');
            $table->year('year');
            $table->tinyInteger('month');
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('company_period', function (Blueprint $table) {
            $table->smallInteger('period_id');
            $table->smallInteger('company_id');
            $table->enum('status', ['not-active', 'open', 'close'])->default('not-active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
        Schema::dropIfExists('company_period');
    }
};
