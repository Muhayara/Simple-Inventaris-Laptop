# UTS PBO: CRUD Inventaris Laptop (Laravel 13)

Aplikasi web **Inventaris Laptop** untuk UTS mata kuliah **Pemrograman Berorientasi Objek**. Studi kasusnya melanjutkan **Sistem Jual Beli Laptop**. Aplikasi mengelola data persediaan laptop dengan pola **Route → Controller → Blade**.

---

## Screenshot Proses CRUD

| Daftar laptop | Form tambah |
| :-: | :-: |
| ![Daftar](docs/uts/01-daftar.png) | ![Form tambah](docs/uts/02-form-tambah.png) |
| **Tambah berhasil** | **Validasi gagal (kode duplikat)** |
| ![Tambah berhasil](docs/uts/03-tambah-berhasil.png) | ![Validasi](docs/uts/04-validasi-gagal.png) |
| **Detail laptop** | **Form edit (nilai lama)** |
| ![Detail](docs/uts/05-detail.png) | ![Edit](docs/uts/06-form-edit.png) |
| **Edit berhasil** | **Pencarian** |
| ![Edit berhasil](docs/uts/07-edit-berhasil.png) | ![Pencarian](docs/uts/08-pencarian.png) |
| **Hapus berhasil** | **Kode tidak ditemukan** |
| ![Hapus](docs/uts/09-hapus-berhasil.png) | ![Tidak ditemukan](docs/uts/10-kode-tidak-ditemukan.png) |
| **Data belum tersedia** | |
| ![Kosong](docs/uts/11-data-kosong.png) | |

---

## Cara Menjalankan

Prasyarat: PHP 8.3+ dan Composer 2.

```bash
git clone https://github.com/Muhayara/Simple-Inventaris-Laptop.git
cd Simple-Inventaris-Laptop
composer install
cp .env.example .env          # SESSION_DRIVER=file sudah diatur di .env.example
php artisan key:generate
php artisan serve
```

Buka `http://127.0.0.1:8000/laptop`.

Tugas pertemuan 7 yang belum sempat upload: [laravel-katalog-laptop-mvc](https://github.com/Muhayara/laravel-katalog-laptop-mvc)
