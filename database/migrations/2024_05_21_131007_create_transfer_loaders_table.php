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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('trans_type');
            $table->date('trans_date');
            $table->string('from_company_code', 20);
            $table->string('from_warehouse', 100)->comment('diisi dengan sloc asal');
            $table->string('to_company_code', 20);
            $table->string('to_warehouse', 100)->comment('diisi dengan sloc tujuan');
            $table->string('transportir', 100)->comment('diisi dengan equipment no');
            $table->string('material_code');
            $table->string('uom')->comment('diisi dengan uom_code');
            $table->decimal('qty', 12, 2);
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
        Schema::dropIfExists('transfers');
    }
};
