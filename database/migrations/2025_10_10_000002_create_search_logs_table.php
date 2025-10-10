<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->decimal('radius_km', 10, 3)->nullable();

            $table->boolean('is_inside')->default(false);
            $table->string('inside_unit')->nullable();
            $table->unsignedBigInteger('inside_unit_id')->nullable();

            $table->string('nearest_unit')->nullable();
            $table->unsignedBigInteger('nearest_unit_id')->nullable();
            $table->decimal('nearest_distance_km', 10, 3)->nullable();

            $table->unsignedBigInteger('stakeholder_id')->nullable()->index();
            $table->unsignedBigInteger('tjsl_id')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
