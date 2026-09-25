<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table("exams", function (Blueprint $table) {
            $table->timestamp("published_at")->nullable()->after("published");
            $table->foreignId("published_by")->nullable()->after("published_at")->constrained("users")->nullOnDelete();
        });

        Schema::table("results", function (Blueprint $table) {
            $table->integer("total_marks")->nullable()->after("marks");
            $table->decimal("average", 5, 2)->nullable()->after("total_marks");
            $table->integer("position")->nullable()->after("average");
            $table->integer("class_size")->nullable()->after("position");
        });
    }

    public function down(): void {
        Schema::table("exams", function (Blueprint $table) {
            $table->dropConstrainedForeignId("published_by");
            $table->dropColumn("published_at");
        });
        Schema::table("results", function (Blueprint $table) {
            $table->dropColumn(["total_marks","average","position","class_size"]);
        });
    }
};