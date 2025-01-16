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
        //
        Schema::table('routes', function (Blueprint $table) {
            // Agregar la columna vehicle_id si no existe
            if (!Schema::hasColumn('routes', 'vehicle_id')) {
                $table->unsignedBigInteger('vehicle_id')->nullable()->after('driver_id');
            }

            // Modificar la columna driver_id para permitir valores NULL
            $table->unsignedBigInteger('driver_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            // Revertir vehicle_id si fue agregada
            if (Schema::hasColumn('routes', 'vehicle_id')) {
                $table->dropColumn('vehicle_id');
            }

            // Revertir driver_id a no permitir NULL
            $table->unsignedBigInteger('driver_id')->nullable(false)->change();
        });
    }
};
