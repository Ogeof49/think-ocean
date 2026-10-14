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
        Schema::table('think_ocean_posts', function (Blueprint $table) {
            $table->string('category', 30)->default('News')->after('slug');
        });

        Schema::create('think_ocean_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('think_ocean_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('think_ocean_subscribers');
        Schema::dropIfExists('think_ocean_messages');

        Schema::table('think_ocean_posts', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
