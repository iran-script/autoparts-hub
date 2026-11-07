<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_makes', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('country_code', 2)->nullable(); $table->timestamps();
        });
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id(); $table->foreignId('vehicle_make_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->string('slug'); $table->timestamps(); $table->unique(['vehicle_make_id', 'slug']);
        });
        Schema::create('vehicle_generations', function (Blueprint $table) {
            $table->id(); $table->foreignId('vehicle_model_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->unsignedSmallInteger('production_start_year')->nullable(); $table->unsignedSmallInteger('production_end_year')->nullable(); $table->text('change_notes')->nullable(); $table->timestamps();
        });
        Schema::create('engines', function (Blueprint $table) {
            $table->id(); $table->string('code')->nullable(); $table->string('name'); $table->string('fuel_type')->nullable(); $table->unsignedSmallInteger('displacement_cc')->nullable(); $table->decimal('power_kw', 8, 2)->nullable(); $table->jsonb('specifications')->nullable(); $table->timestamps();
        });
        Schema::create('transmissions', function (Blueprint $table) {
            $table->id(); $table->string('code')->nullable(); $table->string('name'); $table->string('type')->nullable(); $table->unsignedTinyInteger('gears')->nullable(); $table->jsonb('specifications')->nullable(); $table->timestamps();
        });
        Schema::create('vehicle_variants', function (Blueprint $table) {
            $table->id(); $table->foreignId('vehicle_generation_id')->constrained()->cascadeOnDelete(); $table->foreignId('engine_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('transmission_id')->nullable()->constrained()->nullOnDelete(); $table->string('name'); $table->string('market')->nullable(); $table->unsignedSmallInteger('production_start_year')->nullable(); $table->unsignedSmallInteger('production_end_year')->nullable(); $table->jsonb('identifiers')->nullable(); $table->jsonb('specifications')->nullable(); $table->text('technical_notes')->nullable(); $table->timestamps(); $table->index(['vehicle_generation_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_variants'); Schema::dropIfExists('transmissions'); Schema::dropIfExists('engines'); Schema::dropIfExists('vehicle_generations'); Schema::dropIfExists('vehicle_models'); Schema::dropIfExists('vehicle_makes');
    }
};
