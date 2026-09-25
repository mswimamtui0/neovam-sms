<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
 Schema::create("sports_teams", function (Blueprint $table) {
 $table->id();
 $table->string("name"); // "Football U15"
 $table->string("sport"); // Football, Netball, Athletics
 $table->string("age_group")->nullable();
 $table->foreignId("coach_id")->nullable()->constrained("staff")->nullOnDelete();
 $table->text("notes")->nullable();
 $table->timestamps();
 });

 Schema::create("sports_matches", function (Blueprint $table) {
 $table->id();
 $table->foreignId("team_id")->constrained("sports_teams")->cascadeOnDelete();
 $table->string("opponent");
 $table->date("match_date");
 $table->string("venue")->nullable();
 $table->string("result")->nullable(); // won, lost, draw, pending
 $table->string("score")->nullable();
 $table->text("notes")->nullable();
 $table->timestamps();
 });
 }
 public function down(): void {
 Schema::dropIfExists("sports_matches");
 Schema::dropIfExists("sports_teams");
 }
};