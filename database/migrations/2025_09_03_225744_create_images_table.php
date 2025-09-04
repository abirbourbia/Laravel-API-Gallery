<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('artic_id')->nullable()->unique();
            $table->string('title')->nullable();
            $table->string('image_id')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('favorite')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
