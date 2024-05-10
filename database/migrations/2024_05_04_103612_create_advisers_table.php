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
        Schema::create('advisers', function (Blueprint $table) {
            $table->string('id')->primary(); // will be the id of the adviser and username in the same time
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('specialty')->nullable();
            $table->string('ID_card')->nullable();
            $table->float('points')->default(100);
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('role');
            $table->float('rate')->default(0.0);
            $table->string('bio')->nullable();
            $table->string('role')->default('adviser');
            $table->timestamps();
        });


        DB::unprepared('
        CREATE TRIGGER copy_user_info_to_adviser
        AFTER INSERT ON users
        FOR EACH ROW
        BEGIN
        IF NEW.role = "adviser" THEN
            INSERT INTO advisers (id, id_user, first_name, last_name, points, avatar, email, password, age, gender, role, created_at, updated_at)
            VALUES (NEW.username, NEW.id, NEW.first_name, NEW.last_name, 100, NULL, NEW.email, NEW.password, NEW.age, NEW.gender, "adviser", NOW(), NOW());
        END IF;
        END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisers');
    }
};
