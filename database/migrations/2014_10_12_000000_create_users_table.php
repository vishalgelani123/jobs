<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('user_id')->nullable();
            $table->integer('invite_vendor_id')->nullable();
            $table->string('name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile')->nullable();
            $table->string('user_profile')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('internal_user_or_external')->nullable();
            $table->enum('is_admin_created', ['0', '1'])->default('0');
            $table->enum('is_admin_password_reset', ['0', '1'])->default('0');
            $table->string('otp')->default(0)->nullable();
            $table->tinyInteger('is_otp_verified')->default(0)->nullable();
            $table->dateTime('last_otp_send_date_time')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
