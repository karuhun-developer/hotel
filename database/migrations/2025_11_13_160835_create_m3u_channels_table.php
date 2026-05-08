<?php

use App\Models\M3u\M3uSource;
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
        Schema::create('m3u_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(M3uSource::class)->constrained('m3u_sources')->cascadeOnDelete();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->text('url');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m3u_channels');
    }
};
