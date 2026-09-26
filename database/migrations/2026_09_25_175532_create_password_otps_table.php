<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("password_otps", function (Blueprint $table) {
            $table->id();
            $table->string("identifier"); // phone or email
            $table->string("channel");    // sms | email
            $table->string("otp_code", 10);
            $table->timestamp("expires_at");
            $table->integer("attempts")->default(0);
            $table->boolean("verified")->default(false);
            $table->timestamp("verified_at")->nullable();
            $table->timestamps();

            $table->index(["identifier","channel"]);
            $table->index("expires_at");
        });
    }
    public function down(): void { Schema::dropIfExists("password_otps"); }
};