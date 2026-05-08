<?php

use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
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
        Schema::create('tenant_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(M3uSource::class)->constrained('m3u_sources')->cascadeOnDelete();
            $table->foreignIdFor(M3uChannel::class)->constrained('m3u_channels')->cascadeOnDelete();
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
