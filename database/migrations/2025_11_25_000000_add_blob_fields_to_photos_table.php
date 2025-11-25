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
        // use raw statement to avoid requiring doctrine/dbal for column changes
        DB::statement('ALTER TABLE `photos` MODIFY `url` varchar(191) NULL;');
        Schema::table('photos', function (Blueprint $table) {
            // store binary data directly in the database
            $table->longBlob('data')->nullable()->after('url');
            $table->string('mime', 100)->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn(['data', 'mime']);
        });
        DB::statement('ALTER TABLE `photos` MODIFY `url` varchar(191) NOT NULL;');
    }
};
