<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(true);

            //Others fields
            $table->string('document_number', 15)->nullable();
            $table->enum('document_type', ['Cédula de Identidad', 'NIE', 'DNI', 'Pasaporte', 'Otro'])->default('Cédula de Identidad');
            $table->string('avatar_id')->nullable()->constrained("files"); // photo perfil
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('nationality', ['Chilena', 'Mexicano', 'Otro'])->nullable();
            $table->string('other_nationality')->nullable();
            $table->enum('civil_status', ['Solero(a)', 'Casado(a)', 'Divorsiado(a)', 'Viudo(a)', 'Separado(a)', 'Unión Libre', 'No definido'])->default('No definido');
            $table->enum('sex', ['Masculino', 'Femenino', 'No definido'])->nullable('No definido');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
