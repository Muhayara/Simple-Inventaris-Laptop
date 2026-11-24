<?php

namespace App\Models;

/**
 * Entitas Laptop pada studi kasus Sistem Jual Beli Laptop.
 *
 * Class ini BUKAN model Eloquent (belum memakai database). Ia adalah class PHP
 * biasa yang menerapkan encapsulation: seluruh property bersifat private dan
 * hanya bisa dibaca melalui getter, sehingga data laptop tetap terkontrol.
 */
class Laptop
{
    public const DAFTAR_KATEGORI = ['Gaming', 'Ultrabook', 'Bisnis', 'Workstation', 'Pelajar'];

    public const DAFTAR_LOKASI = ['Etalase Toko', 'Gudang Utama', 'Gudang Cabang', 'Ruang Service'];

    public const DAFTAR_KONDISI = ['Baru', 'Bekas', 'Rusak'];

    public function __construct(
        private string $kode,
        private string $nama,
        private string $kategori,
        private string $lokasi,
        private int $jumlah,
        private string $kondisi,
        private int $harga = 0,
        private string $spesifikasi = '',
    ) {
        $this->kode = strtoupper(trim($kode));
        $this->jumlah = max(0, $jumlah);
        $this->harga = max(0, $harga);
    }

    /**
     * Membentuk object Laptop dari array (data session atau input form).
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            kode: (string) $data['kode'],
            nama: (string) $data['nama'],
            kategori: (string) $data['kategori'],
            lokasi: (string) $data['lokasi'],
            jumlah: (int) $data['jumlah'],
            kondisi: (string) $data['kondisi'],
            harga: (int) ($data['harga'] ?? 0),
            spesifikasi: (string) ($data['spesifikasi'] ?? ''),
        );
    }

    /**
     * Mengubah object menjadi array agar mudah disimpan di session.
     *
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'kode' => $this->kode,
            'nama' => $this->nama,
            'kategori' => $this->kategori,
            'lokasi' => $this->lokasi,
            'jumlah' => $this->jumlah,
            'kondisi' => $this->kondisi,
            'harga' => $this->harga,
            'spesifikasi' => $this->spesifikasi,
        ];
    }

    public function getKode(): string
    {
        return $this->kode;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getKategori(): string
    {
        return $this->kategori;
    }

    public function getLokasi(): string
    {
        return $this->lokasi;
    }

    public function getJumlah(): int
    {
        return $this->jumlah;
    }

    public function getKondisi(): string
    {
        return $this->kondisi;
    }

    public function getHarga(): int
    {
        return $this->harga;
    }

    public function getSpesifikasi(): string
    {
        return $this->spesifikasi;
    }

    /**
     * Nilai persediaan = harga satuan x jumlah unit.
     */
    public function getTotalNilai(): int
    {
        return $this->harga * $this->jumlah;
    }

    public function isTersedia(): bool
    {
        return $this->jumlah > 0 && $this->kondisi !== 'Rusak';
    }

    public function getStatusStok(): string
    {
        if ($this->kondisi === 'Rusak') {
            return 'Tidak Dijual';
        }

        return $this->jumlah > 0 ? 'Tersedia' : 'Stok Habis';
    }
}
