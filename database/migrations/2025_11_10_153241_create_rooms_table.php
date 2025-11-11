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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Tenant\Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Tenant\RoomType::class)->constrained()->cascadeOnDelete();
            $table->string('no');
            $table->string('guest_name')->nullable();
            $table->string('greeting')->nullable();
            $table->string('device_name')->nullable();
            $table->boolean('is_birthday')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
