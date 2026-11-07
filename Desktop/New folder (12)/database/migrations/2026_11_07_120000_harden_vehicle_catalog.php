<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['vehicle_makes', 'vehicle_models', 'vehicle_generations', 'engines', 'transmissions', 'vehicle_variants'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) { $table->boolean('is_active')->default(true); });
        }
        Schema::table('vehicle_generations', function (Blueprint $table) { $table->string('slug')->nullable(); });
        Schema::table('engines', function (Blueprint $table) { $table->string('normalized_code')->nullable(); });
        Schema::table('transmissions', function (Blueprint $table) { $table->string('normalized_code')->nullable(); });

        Schema::create('vehicle_data_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('source_type');
            $table->string('uri')->nullable();
            $table->string('license')->nullable();
            $table->string('verification_status')->default('UNVERIFIED');
            $table->date('last_reviewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_variant_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_variant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_data_source_id')->constrained()->cascadeOnDelete();
            $table->string('evidence_type');
            $table->string('verification_status')->default('UNVERIFIED');
            $table->date('last_reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['vehicle_variant_id', 'vehicle_data_source_id']);
        });

        Schema::table('vehicle_models', function (Blueprint $table) { $table->unique(['vehicle_make_id', 'name']); });
        Schema::table('vehicle_generations', function (Blueprint $table) { $table->unique(['vehicle_model_id', 'name']); });
        Schema::table('engines', function (Blueprint $table) { $table->unique(['normalized_code']); });
        Schema::table('transmissions', function (Blueprint $table) { $table->unique(['normalized_code']); });
    }

    public function down(): void
    {
        Schema::table('transmissions', function (Blueprint $table) { $table->dropUnique(['normalized_code']); });
        Schema::table('engines', function (Blueprint $table) { $table->dropUnique(['normalized_code']); });
        Schema::table('vehicle_generations', function (Blueprint $table) { $table->dropUnique(['vehicle_model_id', 'name']); });
        Schema::table('vehicle_models', function (Blueprint $table) { $table->dropUnique(['vehicle_make_id', 'name']); });
        Schema::dropIfExists('vehicle_variant_sources'); Schema::dropIfExists('vehicle_data_sources');
        Schema::table('vehicle_generations', function (Blueprint $table) { $table->dropColumn('slug'); });
        Schema::table('engines', function (Blueprint $table) { $table->dropColumn('normalized_code'); });
        Schema::table('transmissions', function (Blueprint $table) { $table->dropColumn('normalized_code'); });
        foreach (['vehicle_variants', 'transmissions', 'engines', 'vehicle_generations', 'vehicle_models', 'vehicle_makes'] as $tableName) Schema::table($tableName, fn (Blueprint $table) => $table->dropColumn('is_active'));
    }
};
