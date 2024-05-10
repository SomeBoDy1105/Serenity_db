<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; // Add this line
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->string('id')->primary(); // will be the id of the client and username in the same time
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            // $table->string('username')->unique();
            $table->float('points')->default(100);
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('role')->default('cient');
            $table->timestamps();
        });

        DB::unprepared('
        CREATE TRIGGER copy_user_info_to_client
        AFTER INSERT ON users
        FOR EACH ROW
        BEGIN
        IF NEW.role = "client" THEN
            INSERT INTO clients (id, id_user, first_name, last_name, points, avatar, email, password, age, gender, role, created_at, updated_at)
            VALUES (NEW.username, NEW.id, NEW.first_name, NEW.last_name, 100, NULL, NEW.email, NEW.password, NEW.age, NEW.gender, "client", NOW(), NOW());
        END IF;
        END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
