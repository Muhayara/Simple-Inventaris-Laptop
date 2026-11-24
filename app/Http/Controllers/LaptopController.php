<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Repositories\LaptopRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Controller CRUD Inventaris Laptop (studi kasus Sistem Jual Beli Laptop).
 *
 * Alur: Route -> LaptopController -> Blade View.
 * Data disimpan di session (SESSION_DRIVER=file) melalui LaptopRepository.
 */
class LaptopController extends Controller
{
    public function __construct(private LaptopRepository $laptopRepository) {}

    /**
     * GET /laptop
     * Mengambil data laptop dari session dan mengirimkannya ke halaman daftar.
     */
    public function index(Request $request): View
    {
        $kataKunci = (string) $request->query('cari', '');
        $kategoriDipilih = (string) $request->query('kategori', '');

        $daftarLaptop = $this->laptopRepository->search($kataKunci, $kategoriDipilih);
        $totalSemuaLaptop = count($this->laptopRepository->all());
        $daftarKategori = Laptop::DAFTAR_KATEGORI;

        return view('laptop.index', compact(
            'daftarLaptop',
            'totalSemuaLaptop',
            'daftarKategori',
            'kataKunci',
            'kategoriDipilih',
        ));
    }

    /**
     * GET /laptop/create
     * Menampilkan form tambah laptop.
     */
    public function create(): View
    {
        return view('laptop.create', $this->dataPilihanForm());
    }

    /**
     * POST /laptop
     * Menerima input form, membentuk object Laptop, menyimpannya di session, lalu redirect.
     */
    public function store(Request $request): RedirectResponse
    {
        $dataValid = $request->validate(
            $this->aturanValidasi(['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\-]+$/']),
            $this->pesanValidasi()
        );

        if ($this->laptopRepository->exists($dataValid['kode'])) {
            return back()
                ->withInput()
                ->withErrors(['kode' => 'Kode '.strtoupper($dataValid['kode']).' sudah digunakan laptop lain.']);
        }

        $laptop = Laptop::fromArray($dataValid);
        $this->laptopRepository->save($laptop);

        return redirect()
            ->route('laptop.index')
            ->with('success', 'Laptop '.$laptop->getNama().' ('.$laptop->getKode().') berhasil ditambahkan.');
    }

    /**
     * GET /laptop/{kode}
     * Menampilkan detail satu laptop berdasarkan kode.
     */
    public function show(string $kode): View|RedirectResponse
    {
        $laptop = $this->laptopRepository->find($kode);

        if ($laptop === null) {
            return $this->redirectTidakDitemukan($kode);
        }

        return view('laptop.show', compact('laptop'));
    }

    /**
     * GET /laptop/{kode}/edit
     * Menampilkan form edit dengan data laptop yang dipilih.
     */
    public function edit(string $kode): View|RedirectResponse
    {
        $laptop = $this->laptopRepository->find($kode);

        if ($laptop === null) {
            return $this->redirectTidakDitemukan($kode);
        }

        return view('laptop.edit', array_merge(compact('laptop'), $this->dataPilihanForm()));
    }

    /**
     * PUT/PATCH /laptop/{kode}
     * Memperbarui data laptop pada session berdasarkan kode.
     */
    public function update(Request $request, string $kode): RedirectResponse
    {
        $laptopLama = $this->laptopRepository->find($kode);

        if ($laptopLama === null) {
            return $this->redirectTidakDitemukan($kode);
        }

        // Kode adalah kunci data, sehingga tidak ikut diubah.
        $dataValid = $request->validate($this->aturanValidasi(), $this->pesanValidasi());
        $dataValid['kode'] = $laptopLama->getKode();

        $laptop = Laptop::fromArray($dataValid);
        $this->laptopRepository->save($laptop);

        return redirect()
            ->route('laptop.index')
            ->with('success', 'Laptop '.$laptop->getNama().' ('.$laptop->getKode().') berhasil diperbarui.');
    }

    /**
     * DELETE /laptop/{kode}
     * Menghapus laptop dari session berdasarkan kode.
     */
    public function destroy(string $kode): RedirectResponse
    {
        $laptop = $this->laptopRepository->find($kode);

        if ($laptop === null) {
            return $this->redirectTidakDitemukan($kode);
        }

        $this->laptopRepository->delete($laptop->getKode());

        return redirect()
            ->route('laptop.index')
            ->with('success', 'Laptop '.$laptop->getNama().' ('.$laptop->getKode().') berhasil dihapus.');
    }

    /**
     * POST /laptop/reset (fitur tambahan)
     * Mengembalikan data session ke data contoh awal.
     */
    public function reset(): RedirectResponse
    {
        $this->laptopRepository->reset();

        return redirect()
            ->route('laptop.index')
            ->with('info', 'Data laptop dikembalikan ke data contoh awal.');
    }

    /**
     * Respons ketika kode laptop tidak ditemukan: kembali ke daftar dengan pesan.
     */
    private function redirectTidakDitemukan(string $kode): RedirectResponse
    {
        return redirect()
            ->route('laptop.index')
            ->with('error', 'Laptop dengan kode '.strtoupper($kode).' tidak ditemukan.');
    }

    /**
     * Pilihan untuk input select/radio pada form tambah dan edit.
     *
     * @return array<string, array<int, string>>
     */
    private function dataPilihanForm(): array
    {
        return [
            'daftarKategori' => Laptop::DAFTAR_KATEGORI,
            'daftarLokasi' => Laptop::DAFTAR_LOKASI,
            'daftarKondisi' => Laptop::DAFTAR_KONDISI,
        ];
    }

    /**
     * @param  array<int, string>|null  $aturanKode
     * @return array<string, mixed>
     */
    private function aturanValidasi(?array $aturanKode = null): array
    {
        $aturan = [
            'nama' => ['required', 'string', 'max:100'],
            'kategori' => ['required', Rule::in(Laptop::DAFTAR_KATEGORI)],
            'lokasi' => ['required', Rule::in(Laptop::DAFTAR_LOKASI)],
            'jumlah' => ['required', 'integer', 'min:0', 'max:9999'],
            'kondisi' => ['required', Rule::in(Laptop::DAFTAR_KONDISI)],
            'harga' => ['required', 'integer', 'min:0'],
            'spesifikasi' => ['nullable', 'string', 'max:255'],
        ];

        if ($aturanKode !== null) {
            $aturan = ['kode' => $aturanKode] + $aturan;
        }

        return $aturan;
    }

    /**
     * @return array<string, string>
     */
    private function pesanValidasi(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'min' => 'Kolom :attribute minimal :min.',
            'max' => 'Kolom :attribute maksimal :max.',
            'in' => 'Pilihan :attribute tidak valid.',
            'kode.regex' => 'Kode hanya boleh berisi huruf, angka, dan tanda strip (-).',
        ];
    }
}
