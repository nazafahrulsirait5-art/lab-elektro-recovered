<?php

namespace App\Controllers;

class GraphController extends BaseController
{
    public function index()
    {
        return view('graph');
    }

    public function getData()
    {
        $db = \Config\Database::connect();
        
        // 1. Get Users (Mahasiswa yang pernah meminjam)
        $usersBuilder = $db->table('users');
        $usersBuilder->select('users.username, users.nama_lengkap, users.no_hp');
        $usersBuilder->where('role', 'mahasiswa');
        $users = $usersBuilder->get()->getResult();

        // 2. Get Alat
        $alatBuilder = $db->table('alat');
        $alatBuilder->select('id, nama_alat, jumlah_total');
        $alats = $alatBuilder->get()->getResult();

        // 3. Get Transaksi (Edges)
        $txBuilder = $db->table('transaksi');
        $txBuilder->select('username, id_alat, status_pinjam, jumlah_pinjam, batas_waktu');
        $transactions = $txBuilder->get()->getResult();

        $nodes = [];
        $edges = [];

        // Build Edges and Collect Active Node IDs
        $activeUsers = [];
        $activeAlats = [];
        
        foreach ($transactions as $t) {
            $activeUsers[] = $t->username;
            $activeAlats[] = $t->id_alat;
            
            $statusStr = '';
            if ($t->status_pinjam == 'Dipinjam') {
                $color = '#dc2626'; // Merah kuat
                $statusStr = "<b>Dipinjam (" . $t->jumlah_pinjam . ")</b>\ns/d " . date('d M', strtotime($t->batas_waktu));
            } elseif ($t->status_pinjam == 'Menunggu Persetujuan') {
                $color = '#d97706'; // Orange kuat
                $statusStr = "<b>Menunggu (" . $t->jumlah_pinjam . ")</b>";
            } else {
                $color = '#16a34a'; // Hijau kuat
                $statusStr = "<b>Selesai (" . $t->jumlah_pinjam . ")</b>";
            }

            $edges[] = [
                'from' => 'u_' . $t->username,
                'to' => 'a_' . $t->id_alat,
                'label' => $statusStr,
                'arrows' => 'to',
                'color' => ['color' => $color, 'highlight' => $color],
                'font' => ['color' => $color, 'background' => 'white', 'strokeWidth' => 0, 'multi' => 'html']
            ];
        }

        // Build User Nodes (Hanya yang memiliki transaksi)
        foreach ($users as $u) {
            if (in_array($u->username, $activeUsers)) {
                $nodes[] = [
                    'id' => 'u_' . $u->username,
                    'label' => "<b>" . $u->nama_lengkap . "</b>\n(Mhs)",
                    'nama_lengkap' => $u->nama_lengkap,
                    'no_hp' => $u->no_hp,
                    'title' => 'Klik untuk lihat detail & hubungi WA',
                    'shape' => 'circularImage',
                    'image' => 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
                    'color' => [
                        'border' => '#2563eb', // Biru terang
                        'background' => '#ffffff',
                        'highlight' => ['border' => '#1d4ed8']
                    ],
                    'font' => ['color' => '#1e293b', 'multi' => 'html', 'size' => 15]
                ];
            }
        }

        // Build Alat Nodes (Hanya yang dipinjam)
        foreach ($alats as $a) {
            if (in_array($a->id, $activeAlats)) {
                $nodes[] = [
                    'id' => 'a_' . $a->id,
                    'label' => "<b>" . $a->nama_alat . "</b>\n(Stok: " . $a->jumlah_total . ")",
                    'shape' => 'box',
                    'color' => [
                        'background' => '#1e293b', // Biru sangat gelap (slate-800)
                        'border' => '#0f172a',
                        'highlight' => ['background' => '#334155', 'border' => '#0f172a']
                    ],
                    'font' => ['color' => 'white', 'multi' => 'html', 'size' => 16]
                ];
            }
        }


        return $this->response->setJSON([
            'nodes' => $nodes,
            'edges' => $edges
        ]);
    }
}
