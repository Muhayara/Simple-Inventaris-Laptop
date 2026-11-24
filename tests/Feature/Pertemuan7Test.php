<?php

namespace Tests\Feature;

use Tests\TestCase;

class Pertemuan7Test extends TestCase
{
    public function test_halaman_daftar_dan_detail_buku_dapat_diakses(): void
    {
        $this->get('/buku')
            ->assertStatus(200)
            ->assertSee('Daftar Buku')
            ->assertSee('Belajar PHP')
            ->assertSee('Dasar Laravel');

        $this->get('/buku/1')
            ->assertStatus(200)
            ->assertSee('Detail Buku')
            ->assertSee('ID buku: 1');
    }
}
