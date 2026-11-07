<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('currency', 3)->default('IRR');
            $table->string('display_currency', 10)->default('IRT');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->uuid('public_id')->unique();
            $table->string('name');
            $table->string('code', 32);
            $table->string('timezone')->default('Asia/Tehran');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->unique(['organization_id', 'code']);
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 32);
            $table->string('address')->nullable();
            $table->timestamps();
            $table->unique(['store_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('organizations');
    }
};
