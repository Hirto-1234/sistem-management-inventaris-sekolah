<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Ruangan::insert([
            [
                'nama_ruangan' => 'D11'
            ],
            [
                'nama_ruangan' => 'D10'
            ],
            [
                'nama_ruangan' => 'D13'
            ],
            [
                'nama_ruangan' => 'TU'
            ],
        ]);
    }
}
