<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Antrian;

class AntrianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nama' => 'Budi',
                'no_antrian' => 'A001',
                'status' => 'proses',
            ],
            [
                'nama' => 'Siti',
                'no_antrian' => 'A002',
                'status' => 'menunggu',
            ],
            [
                'nama' => 'Joko',
                'no_antrian' => 'A003',
                'status' => 'menunggu',
            ],
        ];
        foreach ($data as $item) {
            Antrian::create($item);
        }
    }
}
