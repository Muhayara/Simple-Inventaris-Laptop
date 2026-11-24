<?php

namespace App\Repositories;

use App\Models\Laptop;
use Illuminate\Contracts\Session\Session;

/**
 * Lapisan data (data layer) untuk entitas Laptop.
 *
 * Semua data laptop disimpan di SESSION (driver file), bukan database.
 * Controller tidak perlu tahu bagaimana data disimpan; ia cukup memanggil
 * method all(), find(), save(), dan delete() milik class ini.
 */
class LaptopRepository
{
    private const SESSION_KEY = 'daftar_laptop';

    public function __construct(private Session $session) {}

    /**
     * Mengambil seluruh laptop dari session (urut berdasarkan kode).
     *
     * @return array<int, Laptop>
     */
    public function all(): array
    {
        $daftarLaptop = array_map(
            fn (array $data): Laptop => Laptop::fromArray($data),
            array_values($this->ambilDataSession())
        );

        usort($daftarLaptop, fn (Laptop $a, Laptop $b): int => strcmp($a->getKode(), $b->getKode()));

        return $daftarLaptop;
    }

    /**
     * Pencarian sederhana berdasarkan kata kunci (kode / nama) dan kategori.
     *
     * @return array<int, Laptop>
     */
    public function search(?string $kataKunci = null, ?string $kategori = null): array
    {
        $kataKunci = strtolower(trim((string) $kataKunci));
        $kategori = trim((string) $kategori);

        return array_values(array_filter($this->all(), function (Laptop $laptop) use ($kataKunci, $kategori): bool {
            $cocokKataKunci = $kataKunci === ''
                || str_contains(strtolower($laptop->getKode()), $kataKunci)
                || str_contains(strtolower($laptop->getNama()), $kataKunci);

            $cocokKategori = $kategori === '' || $laptop->getKategori() === $kategori;

            return $cocokKataKunci && $cocokKategori;
        }));
    }

    public function find(string $kode): ?Laptop
    {
        $data = $this->ambilDataSession()[strtoupper($kode)] ?? null;

        return $data ? Laptop::fromArray($data) : null;
    }

    public function exists(string $kode): bool
    {
        return $this->find($kode) !== null;
    }

    /**
     * Menyimpan laptop baru atau memperbarui laptop dengan kode yang sama.
     */
    public function save(Laptop $laptop): void
    {
        $data = $this->ambilDataSession();
        $data[$laptop->getKode()] = $laptop->toArray();

        $this->session->put(self::SESSION_KEY, $data);
    }

    public function delete(string $kode): bool
    {
        $data = $this->ambilDataSession();
        $kode = strtoupper($kode);

        if (! array_key_exists($kode, $data)) {
            return false;
        }

        unset($data[$kode]);
        $this->session->put(self::SESSION_KEY, $data);

        return true;
    }

    /**
     * Mengembalikan data ke kondisi awal (data contoh).
     */
    public function reset(): void
    {
        $this->session->put(self::SESSION_KEY, $this->dataAwal());
    }

    /**
     * Membaca array laptop dari session. Saat session pertama kali dibuka,
     * data contoh dimasukkan agar halaman daftar tidak kosong.
     *
     * @return array<string, array<string, int|string>>
     */
    private function ambilDataSession(): array
    {
        if (! $this->session->has(self::SESSION_KEY)) {
            $this->session->put(self::SESSION_KEY, $this->dataAwal());
        }

        return $this->session->get(self::SESSION_KEY, []);
    }

    /**
     * Data contoh melanjutkan katalog laptop dari Pertemuan 7.
     *
     * @return array<string, array<string, int|string>>
     */
    private function dataAwal(): array
    {
        $laptops = [
            new Laptop('LPT-G01', 'ASUS ROG Strix G16', 'Gaming', 'Etalase Toko', 5, 'Baru', 32000000,
                'Intel Core i9-13980HX, 32GB RAM DDR5, 1TB NVMe SSD, NVIDIA RTX 4080 12GB'),
            new Laptop('LPT-G02', 'Acer Predator Helios Neo 16', 'Gaming', 'Gudang Utama', 0, 'Baru', 21500000,
                'Intel Core i7-13700HX, 16GB RAM DDR5, 1TB NVMe SSD, NVIDIA RTX 4060 8GB'),
            new Laptop('LPT-U01', 'Lenovo ThinkPad X1 Carbon', 'Ultrabook', 'Etalase Toko', 3, 'Baru', 24000000,
                'Intel Core i7-1365U, 16GB RAM LPDDR5, 512GB NVMe SSD, bobot 1.12 kg'),
            new Laptop('LPT-U02', 'Apple MacBook Air M3', 'Ultrabook', 'Gudang Utama', 4, 'Bekas', 15500000,
                'Apple M3 8-Core, 16GB Unified Memory, 512GB SSD, bobot 1.24 kg'),
        ];

        $data = [];
        foreach ($laptops as $laptop) {
            $data[$laptop->getKode()] = $laptop->toArray();
        }

        return $data;
    }
}
