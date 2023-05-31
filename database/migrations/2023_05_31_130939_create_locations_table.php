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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accident_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->double('lat', 10, 6)->nullable();
            $table->double('long', 10, 6)->nullable();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->string('note')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->unique(['accident_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
