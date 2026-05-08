<?php

use App\Models\Tenant\Food\FoodCategory;
use App\Models\Tenant\Tenant;
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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(FoodCategory::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('version')->default(1);
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
