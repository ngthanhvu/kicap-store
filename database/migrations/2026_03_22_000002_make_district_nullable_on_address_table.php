<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('address')) {
            DB::statement('ALTER TABLE `address` MODIFY `district` VARCHAR(255) NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('address')) {
            DB::statement("UPDATE `address` SET `district` = '' WHERE `district` IS NULL");
            DB::statement('ALTER TABLE `address` MODIFY `district` VARCHAR(255) NOT NULL');
        }
    }
};
