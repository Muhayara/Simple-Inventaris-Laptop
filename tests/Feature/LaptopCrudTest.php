<?php

namespace Tests\Feature;

use Tests\TestCase;

class LaptopCrudTest extends TestCase
{
    /**
     * @return array<string, int|string>
     */
    private function dataLaptopBaru(array $override = []): array
    {
        return array_merge([
            'kode' => 'LPT-B01',
            'nama' => 'Dell Latitude 7440',
            'kategori' => 'Bisnis',
            'lokasi' => 'Gudang Cabang',
            'jumlah' => 7,
            'kondisi' => 'Baru',
            'harga' => 18000000,
            'spesifikasi' => 'Intel Core i5-1345U, 16GB RAM, 512GB SSD',
        ], $override);
    }

    public function test_halaman_daftar_menampilkan_kolom_dan_data_contoh(): void
    {
        $this->get(route('laptop.index'))
            ->assertOk()
            ->assertSeeInOrder(['Kode', 'Nama Laptop', 'Kategori', 'Lokasi', 'Jumlah', 'Kondisi', 'Aksi'])
            ->assertSee('LPT-G01')
            ->assertSee('ASUS ROG Strix G16')
            ->assertSee('Apple MacBook Air M3')
            ->assertSee('Detail')
            ->assertSee('Edit')
            ->assertSee('Hapus');
    }

    public function test_uri_create_tidak_tertangkap_sebagai_kode(): void
    {
        $this->get('/laptop/create')
            ->assertOk()
            ->assertSee('Tambah Laptop')
            ->assertSee('name="_token"', false);
    }

    public function test_tambah_laptop_tersimpan_di_session_dan_redirect_dengan_pesan(): void
    {
        $this->post(route('laptop.store'), $this->dataLaptopBaru())
            ->assertRedirect(route('laptop.index'))
            ->assertSessionHas('success');

        $this->get(route('laptop.index'))
            ->assertSee('berhasil ditambahkan')
            ->assertSee('LPT-B01')
            ->assertSee('Dell Latitude 7440');
    }

    public function test_tambah_laptop_dengan_kode_duplikat_ditolak(): void
    {
        $this->from(route('laptop.create'))
            ->post(route('laptop.store'), $this->dataLaptopBaru(['kode' => 'LPT-G01']))
            ->assertRedirect(route('laptop.create'))
            ->assertSessionHasErrors('kode');
    }

    public function test_tambah_laptop_dengan_input_tidak_valid_ditolak(): void
    {
        $this->from(route('laptop.create'))
            ->post(route('laptop.store'), $this->dataLaptopBaru([
                'nama' => '',
                'jumlah' => -3,
                'kategori' => 'Tidak Ada',
            ]))
            ->assertRedirect(route('laptop.create'))
            ->assertSessionHasErrors(['nama', 'jumlah', 'kategori']);
    }

    public function test_detail_laptop_menampilkan_informasi_lengkap(): void
    {
        $this->get(route('laptop.show', 'LPT-G01'))
            ->assertOk()
            ->assertSee('Detail Laptop')
            ->assertSee('ASUS ROG Strix G16')
            ->assertSee('Etalase Toko')
            ->assertSee('5 unit')
            ->assertSee('Rp 32.000.000')
            ->assertSee('Rp 160.000.000');
    }

    public function test_form_edit_menampilkan_nilai_lama(): void
    {
        $this->get(route('laptop.edit', 'LPT-U01'))
            ->assertOk()
            ->assertSee('value="Lenovo ThinkPad X1 Carbon"', false)
            ->assertSee('value="3"', false)
            ->assertSee('name="_method" value="PUT"', false);
    }

    public function test_update_laptop_memperbarui_data_di_session(): void
    {
        $this->put(route('laptop.update', 'LPT-U01'), $this->dataLaptopBaru([
            'kode' => 'DIABAIKAN',
            'nama' => 'Lenovo ThinkPad X1 Carbon Gen 12',
            'jumlah' => 10,
            'kondisi' => 'Bekas',
        ]))
            ->assertRedirect(route('laptop.index'))
            ->assertSessionHas('success');

        $this->get(route('laptop.show', 'LPT-U01'))
            ->assertSee('Lenovo ThinkPad X1 Carbon Gen 12')
            ->assertSee('10 unit')
            ->assertSee('Bekas');

        $this->get(route('laptop.show', 'DIABAIKAN'))
            ->assertRedirect(route('laptop.index'));
    }

    public function test_patch_juga_didukung_untuk_update(): void
    {
        $this->patch(route('laptop.update', 'LPT-G02'), $this->dataLaptopBaru(['nama' => 'Acer Predator Baru']))
            ->assertRedirect(route('laptop.index'));

        $this->get(route('laptop.show', 'LPT-G02'))->assertSee('Acer Predator Baru');
    }

    public function test_hapus_laptop_menghilangkan_data_dari_session(): void
    {
        $this->delete(route('laptop.destroy', 'LPT-G02'))
            ->assertRedirect(route('laptop.index'))
            ->assertSessionHas('success');

        $this->get(route('laptop.index'))
            ->assertSee('Laptop Acer Predator Helios Neo 16 (LPT-G02) berhasil dihapus.')
            ->assertDontSee(route('laptop.edit', 'LPT-G02'));

        $this->assertFalse(session()->has('daftar_laptop.LPT-G02'));
    }

    public function test_kode_tidak_ditemukan_diarahkan_ke_daftar_dengan_pesan(): void
    {
        foreach (['show' => 'get', 'edit' => 'get', 'update' => 'put', 'destroy' => 'delete'] as $aksi => $method) {
            $this->{$method}(route('laptop.'.$aksi, 'TIDAK-ADA'), $this->dataLaptopBaru())
                ->assertRedirect(route('laptop.index'))
                ->assertSessionHas('error', 'Laptop dengan kode TIDAK-ADA tidak ditemukan.');
        }
    }

    public function test_pesan_ketika_data_belum_tersedia(): void
    {
        foreach (['LPT-G01', 'LPT-G02', 'LPT-U01', 'LPT-U02'] as $kode) {
            $this->delete(route('laptop.destroy', $kode));
        }

        $this->get(route('laptop.index'))
            ->assertOk()
            ->assertSee('Belum ada data laptop.');
    }

    public function test_pencarian_sederhana(): void
    {
        $this->get(route('laptop.index', ['kategori' => 'Gaming']))
            ->assertSee('ASUS ROG Strix G16')
            ->assertDontSee('Apple MacBook Air M3');

        $this->get(route('laptop.index', ['cari' => 'macbook']))
            ->assertSee('Apple MacBook Air M3')
            ->assertDontSee('ASUS ROG Strix G16');

        $this->get(route('laptop.index', ['cari' => 'xyz']))
            ->assertSee('Data laptop tidak ditemukan.');
    }

    public function test_reset_mengembalikan_data_contoh(): void
    {
        $this->delete(route('laptop.destroy', 'LPT-G01'));

        $this->post(route('laptop.reset'))
            ->assertRedirect(route('laptop.index'))
            ->assertSessionHas('info');

        $this->get(route('laptop.index'))->assertSee('ASUS ROG Strix G16');
    }
}
