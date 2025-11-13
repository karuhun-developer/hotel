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
        Schema::create('m3u_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('url');
            $table->string('type')->comment('GET, POST');
            $table->text('headers')->nullable();
            $table->text('body')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m3u_sources');
    }
};
