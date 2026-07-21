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
        Schema::table('temuan', function (Blueprint $table) {
            $table->string('detail_sub_area')->nullable()->after('sub_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temuan', function (Blueprint $table) {
            $table->dropColumn('detail_sub_area');
        });
    }
};
