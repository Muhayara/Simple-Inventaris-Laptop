@extends('layouts.app')

@section('title', 'Detail Laptop')
@section('subtitle', $laptop->getKode().' - '.$laptop->getNama())

@section('content')
    <div class="card">
        <div style="margin-bottom: 12px;">
            <span class="badge badge-cat">{{ $laptop->getKategori() }}</span>
            <span class="badge badge-{{ strtolower($laptop->getKondisi()) }}">{{ $laptop->getKondisi() }}</span>
        </div>

        <h2 style="margin: 0 0 16px;">{{ $laptop->getNama() }}</h2>

        <table class="data">
            <tr>
                <th style="width: 220px;">Kode</th>
                <td><strong>{{ $laptop->getKode() }}</strong></td>
            </tr>
            <tr>
                <th>Nama Laptop</th>
                <td>{{ $laptop->getNama() }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $laptop->getKategori() }}</td>
            </tr>
            <tr>
                <th>Lokasi Penyimpanan</th>
                <td>{{ $laptop->getLokasi() }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>{{ $laptop->getJumlah() }} unit</td>
            </tr>
            <tr>
                <th>Kondisi</th>
                <td>{{ $laptop->getKondisi() }}</td>
            </tr>
            <tr>
                <th>Status Stok</th>
                <td>
                    @if ($laptop->isTersedia())
                        <span class="badge badge-baru">{{ $laptop->getStatusStok() }}</span>
                    @else
                        <span class="badge badge-rusak">{{ $laptop->getStatusStok() }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Harga Satuan</th>
                <td>Rp {{ number_format($laptop->getHarga(), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Nilai Persediaan</th>
                <td><strong>Rp {{ number_format($laptop->getTotalNilai(), 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th>Spesifikasi</th>
                <td>
                    @if ($laptop->getSpesifikasi() !== '')
                        {{ $laptop->getSpesifikasi() }}
                    @else
                        <span class="muted">Belum ada keterangan spesifikasi.</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="form-actions actions">
            <a href="{{ route('laptop.index') }}" class="btn">&larr; Kembali ke Daftar</a>
            <a href="{{ route('laptop.edit', $laptop->getKode()) }}" class="btn btn-warning">Edit</a>
            <form method="POST" action="{{ route('laptop.destroy', $laptop->getKode()) }}"
                  onsubmit="return confirm(@js('Yakin ingin menghapus laptop '.$laptop->getKode().' - '.$laptop->getNama().'?'));">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
@endsection
