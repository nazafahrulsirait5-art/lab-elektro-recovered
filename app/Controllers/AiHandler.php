<?php

namespace App\Controllers;

use App\Models\AlatModel;

class AiHandler extends BaseController
{
    /**
     * AI Virtual Lab Assistant Handler
     * Processes natural language queries about laboratory equipment
     */
    public function chat()
    {
        $message = $this->request->getPost('message');
        $alatModel = new AlatModel();

        // 1. Logika "Pencarian Pintar" (Mock AI)
        // Mencari nama alat atau merk dalam pesan user
        $tools = $alatModel->findAll();
        $foundTool = null;

        foreach ($tools as $tool) {
            if (stripos($message, $tool['nama_alat']) !== false) {
                $foundTool = $tool;
                break;
            }
        }

        // 2. Persona Response
        if ($foundTool) {
            $response = "Halo! Saya Asisten Virtual Lab Elektro. Terkait **" . $foundTool['nama_alat'] . " " . $foundTool['merk'] . "**, saat ini tersedia **" . $foundTool['jumlah_tersedia'] . "** unit. Apakah Anda butuh bantuan untuk prosedur peminjamannya?";
        } else if (stripos($message, 'halo') !== false || stripos($message, 'hi') !== false) {
            $response = "Halo " . session()->get('nama_lengkap') . "! Saya adalah **Virtual Lab Assistant**. Ada yang bisa saya bantu terkait inventaris alat hari ini?";
        } else {
            $response = "Maaf, saya belum mengenali alat yang Anda maksud. Silakan tanyakan tentang nama alat atau merk tertentu yang ada di laboratorium kita.";
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'response' => $response,
            'persona'  => 'Virtual Lab Assistant',
            'timestamp' => date('H:i')
        ]);
    }
}
