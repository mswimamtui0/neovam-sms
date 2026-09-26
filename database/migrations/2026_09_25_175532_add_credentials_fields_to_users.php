<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table("users", function (Blueprint $table) {
            if (!Schema::hasColumn("users","must_change_password")) {
                $table->boolean("must_change_password")->default(false)->after("password");
            }
            if (!Schema::hasColumn("users","credentials_sent_at")) {
                $table->timestamp("credentials_sent_at")->nullable()->after("must_change_password");
            }
            if (!Schema::hasColumn("users","last_login_at")) {
                $table->timestamp("last_login_at")->nullable()->after("credentials_sent_at");
            }
            if (!Schema::hasColumn("users","last_login_ip")) {
                $table->string("last_login_ip")->nullable()->after("last_login_at");
            }
        });
    }
    public function down(): void {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn(["must_change_password","credentials_sent_at","last_login_at","last_login_ip"]);
        });
    }
};