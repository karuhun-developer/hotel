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
        Schema::create('tenant_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Tenant\Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\M3u\M3uSource::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\M3u\M3uChannel::class)->constrained()->cascadeOnDelete();
            $table->string('alias')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_channels');
    }
};
