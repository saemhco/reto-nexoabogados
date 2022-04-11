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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->enum('frecuency', ['Mensual', 'Anual'])->default('Mensual');
            $table->dateTime('start_date')->nullable(); //Fecha de inicio
            $table->dateTime('end_date')->nullable();   //Fecha de fin programada
            $table->dateTime('end_at')->nullable(); //Fecha de fin real
            $table->boolean('renewal')->default(true); //Renovación
            $table->dateTime('renewal_cancel_at')->nullable(); //Fecha de cancelación de renovación
            $table->enum('status', ['active', 'procesing', 'canceled'])->default('procesing'); //Estado de la suscripción
            $table->integer('failed_subscription_attempts')->default(0); //Intentos fallidos de suscripción
            $table->softDeletes();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
