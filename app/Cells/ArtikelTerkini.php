<?php
namespace App\Cells;

use CodeIgniter\View\Cell;
use App\Models\ArtikelModel;
use CodeIgniter\View\View;
use Config\Services;

class ArtikelTerkini extends Cell
{
    public function __construct()
    {
        $cache = Services::cache();
        parent::__construct($cache);
    }

    public function render(?string $library = null, $params = null, int $ttl = 0, ?string $cacheName = null): string
    {
        $model = new ArtikelModel();
        $artikel = $model->orderBy('id', 'DESC')->limit(5)->findAll();
        return view('components/artikel_terkini', ['artikel' => $artikel]);
    }
}