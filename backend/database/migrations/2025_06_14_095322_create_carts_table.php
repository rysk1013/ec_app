<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullable();
            $table->string('gest_session_id', 255)->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('carts', function (Blueprint $table) {
            DB::statement('ALTER TABLE carts ADD CONSTRAINT check_required_user_id_or_gest_session_id CHECK (user_id IS NOT NULL OR gest_session_id IS NOT NULL);');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
