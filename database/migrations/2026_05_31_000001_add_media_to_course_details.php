<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('course_details', function (Blueprint $table) {
            $table->string('pdf_file', 255)->nullable()->after('thumbnail');
            $table->string('video_file', 255)->nullable()->after('pdf_file');
            $table->string('video_url', 500)->nullable()->after('video_file');
        });
    }
    public function down(): void {
        Schema::table('course_details', function (Blueprint $table) {
            $table->dropColumn(['pdf_file', 'video_file', 'video_url']);
        });
    }
};
