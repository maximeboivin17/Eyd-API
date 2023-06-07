<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        {
            // Ajout des handicaps de l'application
            $disabilities = [
                ['name' => 'Handicap physique'],
                ['name' => 'Handicap intellectuel'],
                ['name' => 'Handicap sensoriel'],
                ['name' => 'Handicap psychique'],
                ['name' => 'Handicap invisible'],
            ];

            DB::table('disabilities')->insert($disabilities);
        }
    }
};
