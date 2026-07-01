<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('lessons', 'video_file'))    $table->string('video_file')->nullable();
            if (!Schema::hasColumn('lessons', 'youtube_link'))  $table->string('youtube_link', 500)->nullable();
            if (!Schema::hasColumn('lessons', 'pdf_file'))      $table->string('pdf_file')->nullable();
            if (!Schema::hasColumn('lessons', 'external_link')) $table->string('external_link', 500)->nullable();
        });
    }
    public function down(): void {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['video_file','youtube_link','pdf_file','external_link']);
        });
    }
};