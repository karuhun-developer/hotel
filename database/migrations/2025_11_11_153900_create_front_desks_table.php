<?php

use App\Models\Tenant\Room;
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
        Schema::create('front_desks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Room::class)->constrained()->cascadeOnDelete();
            $table->string('guest_name');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_desks');
    }
};
