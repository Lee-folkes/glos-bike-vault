<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bikes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('nickname');
                $table->string('mpn');
                $table->string('brand');
                $table->string('model');
                $table->string('type');
                $table->integer('wheel_size');
                $table->string('colour');
                $table->integer('num_gears');
                $table->string('brake_type');
                $table->string('suspension');
                $table->string('gender');
                $table->string('age_group');
                $table->string('status')->default('active'); // New status field with default value
                $table->timestamps();
            });
            


        }

    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};
