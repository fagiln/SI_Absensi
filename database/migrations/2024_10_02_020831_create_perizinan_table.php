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
        Schema::create('perizinan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('reason', allowed: ['sakit', 'izin', 'cuti']);
            $table->text('keterangan');
            $table->string('bukti_path');
            $table->enum('status', allowed: ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('keterangan_ditolak')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp(column: 'updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinan');
    }
};
