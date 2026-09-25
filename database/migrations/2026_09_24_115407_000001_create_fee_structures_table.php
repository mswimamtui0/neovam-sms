<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("fee_structures", function (Blueprint $table) {
 $table->id();
 $table->foreignId("school_id")->constrained()->cascadeOnDelete();
 $table->string("level");
 $table->string("term");
 $table->integer("year");
 $table->decimal("tuition_fee", 12, 2)->default(0);
 $table->decimal("transport_fee", 12, 2)->default(0);
 $table->decimal("meal_fee", 12, 2)->default(0);
 $table->decimal("development_fee", 12, 2)->default(0);
 $table->decimal("exam_fee", 12, 2)->default(0);
 $table->decimal("other_fee", 12, 2)->default(0);
 $table->text("notes")->nullable();
 $table->timestamps();

 $table->unique(["school_id","level","term","year"], "unique_fee_structure");
 });
 }
 public function down(): void { Schema::dropIfExists("fee_structures"); }
};