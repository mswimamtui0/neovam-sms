<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("parent_contacts", function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->constrained("staff")->cascadeOnDelete();
            $table->foreignId("student_id")->constrained()->cascadeOnDelete();
            $table->string("contact_type")->default("call");
            $table->text("reason");
            $table->text("outcome")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("parent_contacts"); }
};