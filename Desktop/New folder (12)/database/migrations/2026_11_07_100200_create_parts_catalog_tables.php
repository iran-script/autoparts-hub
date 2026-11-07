<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('part_categories', function (Blueprint $table) {
            $table->id(); $table->foreignId('parent_id')->nullable()->constrained('part_categories')->nullOnDelete(); $table->string('name'); $table->string('slug'); $table->text('description')->nullable(); $table->timestamps(); $table->unique(['parent_id', 'slug']);
        });
        Schema::create('brands', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('country_code', 2)->nullable(); $table->string('status')->default('active'); $table->timestamps(); });
        Schema::create('technical_parts', function (Blueprint $table) { $table->id(); $table->uuid('public_id')->unique(); $table->foreignId('part_category_id')->nullable()->constrained()->nullOnDelete(); $table->string('name'); $table->string('english_name')->nullable(); $table->text('description')->nullable(); $table->jsonb('specifications')->nullable(); $table->string('status')->default('draft'); $table->timestamps(); $table->softDeletes(); });
        Schema::create('part_numbers', function (Blueprint $table) { $table->id(); $table->foreignId('technical_part_id')->constrained()->cascadeOnDelete(); $table->string('number'); $table->string('type'); $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete(); $table->string('normalized_number'); $table->timestamps(); $table->unique(['type', 'normalized_number', 'brand_id']); });
        Schema::create('products', function (Blueprint $table) { $table->id(); $table->uuid('public_id')->unique(); $table->foreignId('technical_part_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete(); $table->string('sku'); $table->string('manufacturer_part_number')->nullable(); $table->string('barcode')->nullable(); $table->string('name'); $table->string('unit')->default('عدد'); $table->jsonb('packaging')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps(); $table->softDeletes(); $table->unique('sku'); $table->unique('barcode'); });
        Schema::create('catalog_sources', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('source_type'); $table->string('uri')->nullable(); $table->string('license')->nullable(); $table->text('notes')->nullable(); $table->timestamps(); });
        Schema::create('part_fitments', function (Blueprint $table) { $table->id(); $table->foreignId('technical_part_id')->constrained()->cascadeOnDelete(); $table->foreignId('vehicle_variant_id')->constrained()->cascadeOnDelete(); $table->string('status')->default('UNVERIFIED'); $table->jsonb('constraints')->nullable(); $table->text('notes')->nullable(); $table->timestamps(); $table->unique(['technical_part_id', 'vehicle_variant_id']); });
        Schema::create('fitment_evidence', function (Blueprint $table) { $table->id(); $table->foreignId('part_fitment_id')->constrained()->cascadeOnDelete(); $table->foreignId('catalog_source_id')->nullable()->constrained()->nullOnDelete(); $table->string('evidence_type'); $table->string('version')->nullable(); $table->date('observed_at')->nullable(); $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); $table->text('notes')->nullable(); $table->timestamps(); });
    }

    public function down(): void
    {
        Schema::dropIfExists('fitment_evidence'); Schema::dropIfExists('part_fitments'); Schema::dropIfExists('catalog_sources'); Schema::dropIfExists('products'); Schema::dropIfExists('part_numbers'); Schema::dropIfExists('technical_parts'); Schema::dropIfExists('brands'); Schema::dropIfExists('part_categories');
    }
};
