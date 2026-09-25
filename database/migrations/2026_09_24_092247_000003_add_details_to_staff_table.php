<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
 Schema::table("staff", function (Blueprint $table) {
 $table->string("staff_type")->nullable();
 $table->date("dob")->nullable();
 $table->string("nida")->nullable();
 $table->string("photo")->nullable();
 $table->string("address")->nullable();
 $table->date("employment_date")->nullable();
 $table->string("employment_type")->nullable();
 $table->string("qualification")->nullable();
 $table->string("field_of_study")->nullable();
 $table->string("institution")->nullable();
 $table->integer("year_graduated")->nullable();
 $table->string("emergency_contact_name")->nullable();
 $table->string("emergency_contact_phone")->nullable();
 $table->string("bank_name")->nullable();
 $table->string("bank_account")->nullable();
 $table->string("tin_number")->nullable();
 });
 }

 public function down(): void
 {
 Schema::table("staff", function (Blueprint $table) {
 $table->dropColumn([
 "staff_type","dob","nida","photo","address",
 "employment_date","employment_type",
 "qualification","field_of_study","institution","year_graduated",
 "emergency_contact_name","emergency_contact_phone",
 "bank_name","bank_account","tin_number",
 ]);
 });
 }
};