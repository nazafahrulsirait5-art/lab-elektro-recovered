<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_alat' => 'Signal Generator',
                'merk' => 'Rigol DG1022Z',
                'jumlah_tersedia' => 5,
                'jumlah_maintenance' => 1,
                'jumlah_rusak' => 0,
            ],
            [
                'nama_alat' => 'Spectrum Analyzer',
                'merk' => 'Keysight N9320B',
                'jumlah_tersedia' => 2,
                'jumlah_maintenance' => 0,
                'jumlah_rusak' => 0,
            ],
            [
                'nama_alat' => 'Oscilloscope Digital',
                'merk' => 'Tektronix TBS1052B',
                'jumlah_tersedia' => 12,
                'jumlah_maintenance' => 2,
                'jumlah_rusak' => 1,
            ],
            [
                'nama_alat' => 'Mikroskop Elektron (SEM)',
                'merk' => 'Hitachi TM4000',
                'jumlah_tersedia' => 1,
                'jumlah_maintenance' => 0,
                'jumlah_rusak' => 0,
            ],
            [
                'nama_alat' => 'Motor Stepper NEMA 17',
                'merk' => 'Hanpose',
                'jumlah_tersedia' => 25,
                'jumlah_maintenance' => 0,
                'jumlah_rusak' => 3,
            ],
            [
                'nama_alat' => 'PLC Controller',
                'merk' => 'Omron CP1E',
                'jumlah_tersedia' => 8,
                'jumlah_maintenance' => 1,
                'jumlah_rusak' => 0,
            ],
            [
                'nama_alat' => 'Multimeter Digital',
                'merk' => 'Fluke 115',
                'jumlah_tersedia' => 30,
                'jumlah_maintenance' => 0,
                'jumlah_rusak' => 5,
            ],
            [
                'nama_alat' => 'Power Supply DC Adjustable',
                'merk' => 'Korad KA3005D',
                'jumlah_tersedia' => 15,
                'jumlah_maintenance' => 2,
                'jumlah_rusak' => 1,
            ],
            [
                'nama_alat' => 'Function Generator',
                'merk' => 'GW Instek AFG-21225',
                'jumlah_tersedia' => 10,
                'jumlah_maintenance' => 1,
                'jumlah_rusak' => 0,
            ],
            [
                'nama_alat' => 'Soldering Station',
                'merk' => 'Hakko FX-888D',
                'jumlah_tersedia' => 20,
                'jumlah_maintenance' => 3,
                'jumlah_rusak' => 2,
            ]
        ];

        $this->db->table('alat')->insertBatch($data);
    }
}
