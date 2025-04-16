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

