<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname')->default('Anonim');
            $table->timestamp('registered_at')->useCurrent();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Create trigger to check unicity of nickname
        DB::unprepared('
            CREATE TRIGGER unique_nickname_before_insert
            BEFORE INSERT ON users
            FOR EACH ROW
            BEGIN
                IF NEW.nickname != "Anonim" AND 
                   (SELECT COUNT(*) FROM users WHERE nickname = NEW.nickname) > 0 THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Duplicate nickname not allowed";
                END IF;
            END
        ');

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        DB::unprepared('DROP TRIGGER IF EXISTS unique_nickname_before_insert');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
