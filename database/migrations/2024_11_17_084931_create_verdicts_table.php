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
        Schema::create('verdicts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('litigant');
            $table->string('defendant'); 
            $table->string('case_number'); 
            $table->enum('case_type',
            [
                'Gugatan',
                'Permohonan',
            ]); 
            $table->string('sub_case_type'); 
            $table->date('verdict_date'); 
            $table->string('url_to_valid_verdict'); 
            $table->string('file_verdict_path')->nullable();
            $table->string('file_verdict_stamped_path')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verdicts');
    }
};
