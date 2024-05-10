<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviewers', function (Blueprint $table) {
            $table->string('id')->primary(); // will be the id of the reviwer and username in the same time
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('ID_card')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('role')->default('reviewer');
            $table->timestamps();
        });
        DB::unprepared('
        CREATE TRIGGER copy_user_info_to_reviewer
        AFTER INSERT ON users
        FOR EACH ROW
        BEGIN
        IF NEW.role = "reviewer" THEN
            INSERT INTO reviewers (id, id_user, first_name, last_name, avatar, email, password, ID_card, age, gender, role, created_at, updated_at)
            SELECT NEW.username, NEW.id, NEW.first_name, NEW.last_name, NEW.avatar, NEW.email, NEW.password, NEW.ID_card, NEW.age, NEW.gender, "reviewer", NOW(), NOW();
        END IF;
        END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewers');
    }
};
