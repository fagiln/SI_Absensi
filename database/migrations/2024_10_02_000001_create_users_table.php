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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->unique();
            $table->string('username', 20)->unique();
            $table->string('email', 50)->unique()->nullable();
            $table->string('password', 255);
            $table->string('name', 50)->nullable();
            $table->text('jabatan')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('avatar')->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->foreignId('department_id')->nullable()->constrained('department')->onUpdate('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp(column: 'updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
