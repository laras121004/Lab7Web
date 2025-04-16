| Keterangan | Data |
|----|----|
|Nama|Laras Sakti|
|Nim|312310627|
|Kelas|TI.23.A6|
|Mata Kulial|Pemograman Web 2|

# Modul 1: PHP Framework (CodeIgniter)

## Tujuan
- Memahami konsep dasar Framework dan MVC.
- Instalasi dan konfigurasi CodeIgniter 4.
- Membuat program sederhana dengan Controller, Model, dan View.
  
## Langkah-langkah
- Konfigurasi PHP & Web Server: Mengaktifkan ekstensi seperti php-json, php-mysqlnd, dll.
  - Instalasi CodeIgniter: Mengunduh secara manual dan mengonfigurasi env.
  - Menjalankan CLI (php spark): Untuk debugging dan pengembangan.
- Memahami Struktur Direktori: Memahami folder utama seperti app, public, vendor.
- Membuat Routing & Controller:
  - Menentukan routing di app/Config/Routes.php.
  - Membuat Controller di app/Controllers/Page.php.
- Membuat View: Implementasi tampilan dengan file app/Views/about.php.
- Layout dengan CSS: Memisahkan template header.php dan footer.php untuk efisiensi.
  
# Modul 2: Framework Lanjutan (CRUD)

## Tujuan
- Memahami konsep Model dan CRUD.
- Membuat aplikasi CRUD dengan CodeIgniter 4.
- Mengelola data Artikel dalam database MySQL.

## Langkah-langkah Praktikum
## Persiapan.
Untuk memulai membuat aplikasi CRUD sederhana, yang perlu disiapkan adalah database
server menggunakan MySQL. Pastikan MySQL Server sudah dapat dijalankan melalui
XAMPP.

## Membuat Database
~~~
CREATE DATABASE lab_ci4;
~~~

## Membuat Tabel

~~~
CREATE TABLE artikel (
id INT(11) auto_increment,
judul VARCHAR(200) NOT NULL,
isi TEXT,
gambar VARCHAR(200),
status TINYINT(1) DEFAULT 0,
slug VARCHAR(200),
PRIMARY KEY(id)
);
~~~

### Konfigurasi koneksi database
Selanjutnya membuat konfigurasi untuk menghubungkan dengan database server. Konfigurasi
dapat dilakukan dengan du acara, yaitu pada file app/config/database.php atau menggunakan
file .env. Pada praktikum ini kita gunakan konfigurasi pada file .env.

![Cuplikan layar 2025-04-15 185546](https://github.com/user-attachments/assets/5676e557-5e24-42e2-a798-c65d10f8bcc4)

### Membuat Model
Selanjutnya adalah membuat Model untuk memproses data Artikel. Buat file baru pada
direktori app/Models dengan nama ArtikelModel.php

~~~
<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table      = 'artikel';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['judul', 'isi', 'status', 'slug', 'gambar'];
}
~~~

![Cuplikan layar 2025-04-15 185819](https://github.com/user-attachments/assets/b2104ace-ad42-4a3c-bc59-1b731e01b53f)

### Membuat Controller
Buat Controller baru dengan nama Artikel.php pada direktori app/Controllers.

~~~
<?php

namespace App\Controllers;

use App\Models\ArtikelModel;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->findAll();

        return view('artikel/index', compact('artikel', 'title'));
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        $artikel = $model->where(['slug' => $slug])->first();

        // Menampilkan error apabila data tidak ada.
        if (!$artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];
        return view('artikel/detail', compact('artikel', 'title'));
    }

    public function admin_index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->findAll();

        return view('artikel/admin_index', compact('artikel', 'title'));
    }

    public function add()
    {
        // Validasi data.
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $artikel = new ArtikelModel();
            $artikel->insert([
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'slug' => url_title($this->request->getPost('judul')),
            ]);
            return redirect('admin/artikel');
        }

        $title = "Tambah Artikel";
        return view('artikel/form_add', compact('title'));
    }

    public function edit($id)
    {
        $artikel = new ArtikelModel();

        // Validasi data.
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $artikel->update($id, [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
            ]);
            return redirect('admin/artikel');
        }

        // Ambil data lama.
        $data = $artikel->where('id', $id)->first();
        $title = "Edit Artikel";

        return view('artikel/form_edit', compact('title', 'data'));
    }

    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE artikel AUTO_INCREMENT = 1");
        
        return redirect('admin/artikel');
    }
}
~~~

![Cuplikan layar 2025-04-15 190106](https://github.com/user-attachments/assets/3860db5c-b89e-4e14-8287-92039638fa3f)

![Cuplikan layar 2025-04-15 190138](https://github.com/user-attachments/assets/979d06d0-7b41-49d3-ace7-a3a84aab9d2e)

![Cuplikan layar 2025-04-15 190224](https://github.com/user-attachments/assets/144c849d-f5eb-4f8e-b91e-5c5a393552ca)

![Cuplikan layar 2025-04-15 190238](https://github.com/user-attachments/assets/649183b8-047f-423b-b5b0-f78110367846)

### Membuat View
Buat direktori baru dengan nama artikel pada direktori app/views, kemudian buat file baru
dengan nama index.php.

~~~
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php if ($artikel) : ?>
    <?php foreach ($artikel as $row) : ?>
        <article class="entry">
            <h2>
                <a href="<?= base_url('/artikel/' . $row['slug']); ?>">
                    <?= $row['judul']; ?>
                </a>
            </h2>
            <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= $row['judul']; ?>">
            <p><?= substr($row['isi'], 0, 200); ?></p>
        </article>
        <hr class="divider" />
    <?php endforeach; ?>
<?php else : ?>
    <article class="entry">
        <h2>Belum ada data.</h2>
    </article>
<?php endif; ?>

<?= $this->endSection() ?>
~~~

![Cuplikan layar 2025-04-15 190538](https://github.com/user-attachments/assets/0dedea8a-0ef1-4a0f-9f79-4cc623ee971d)












