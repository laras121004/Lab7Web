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

# Modul 3: View Layout dan View Cell

## Tujuan
- Menggunakan View Layout untuk membuat template tampilan.
- Implementasi View Cell untuk komponen UI modular.

## Langkah-langkah
- Membuat Layout Utama (main.php):
  - Template utama dengan renderSection('content') ?>.
  - Navigasi dan sidebar dengan View Cell.
- Memodifikasi View:
  - Menggunakan extend('layout/main') agar tampilan konsisten.
- Membuat View Cell (ArtikelTerkini.php):
  - Menampilkan daftar artikel terbaru secara modular.
- Membuat View untuk View Cell (artikel_terkini.php):
  - Menampilkan daftar artikel sebagai widget sidebar.

## Cara Menjalankan
- Pastikan XAMPP/MySQL sudah berjalan.
- Import database dari file lab_ci4.sql.
- Jalankan CodeIgniter dengan perintah:

` php spark serve `

![Cuplikan layar 2025-06-01 002709](https://github.com/user-attachments/assets/bdc9459c-525b-4887-995f-00adcfb7917c)

- jalankan dengan link dibawah ini

`http://localhost:8080/home
 http://localhost:8080/admin/artikel (untuk admin)`

## isi router.php

 ` $routes->get('/home', 'page::home');
   $routes->get('/about', 'Page::about');
   $routes->get('/contact', 'Page::contact');

   $routes->get('/artikel', 'Artikel::index');
   $routes->get('/artikel/(:any)', 'Artikel::view/$1');
   $routes->group('admin', function($routes) {
       $routes->get('artikel', 'Artikel::admin_index');
       $routes->add('artikel/add', 'Artikel::add');
       $routes->add('artikel/edit/(:any)', 'Artikel::edit/$1');
       $routes->get('artikel/delete/(:any)','Artikel::delete/$1');
       }); `

# dokumentasi

## Tampilan codeigniter 4

## Tampilan jika error

![Cuplikan layar 2025-03-18 182320](https://github.com/user-attachments/assets/3b5b7cf3-5baa-42b7-997a-c339b6f9ef18)

## Halaman home

![Cuplikan layar 2025-04-14 013542](https://github.com/user-attachments/assets/49bbf68d-7ad8-4c00-8dbb-1b5e9b736e2b)

## Halaman Artikel

![Cuplikan layar 2025-04-14 013558](https://github.com/user-attachments/assets/91a9e6ef-f621-49ee-bf86-470b1bedfe6c)

## Artikel Detai

![image](https://github.com/user-attachments/assets/7c367b86-3575-478d-807b-b941b8ae1b0c)

## Halaman About

![Cuplikan layar 2025-03-18 194258](https://github.com/user-attachments/assets/c8f5be36-df22-446b-a6b9-4e137da03134)

## Halaman Contact

![image](https://github.com/user-attachments/assets/31c824e8-d16b-48c7-bb3e-744430dcc201)

# Halaman Admin

![Cuplikan layar 2025-04-15 195324](https://github.com/user-attachments/assets/8dfb0d5a-c4e6-4b1d-9e35-b355ba281841)

## Admin add

![Cuplikan layar 2025-06-03 191006](https://github.com/user-attachments/assets/65c7ab8b-e7d5-4478-98d0-af0bc2b229b0)


## Admin update

![Cuplikan layar 2025-06-03 190910](https://github.com/user-attachments/assets/15915e4d-af22-4799-828a-9df96c13a1b8)








    







