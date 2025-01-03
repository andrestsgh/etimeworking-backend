<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_surname');
            $table->string('second_surname');
            $table->date('birthdate')->nullable();
            $table->timestamp('register_date');
            $table->string('phone', 20);
            $table->string('dni', 9)->unique();
            $table->longText('address');
            $table->string('city');
            $table->longText('url_picture')->nullable();
            $table->enum('role', ['admin', 'employee'])->default('employee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
