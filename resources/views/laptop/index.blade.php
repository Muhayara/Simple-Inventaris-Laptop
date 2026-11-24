@extends('layouts.app')

@section('title', 'Daftar Laptop')
@section('subtitle', 'Data persediaan laptop yang tersimpan di session aplikasi.')

@section('title-actions')
    <a href="{{ route('laptop.create') }}" class="btn btn-primary">+ Tambah Laptop</a>
@endsection

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('laptop.index') }}" class="search-bar">
            <input type="text" name="cari" value="{{ $kataKunci }}" placeholder="Cari kode atau nama laptop...">
            <select name="kategori">
                <option value="">Semua Kategori</option>
                @foreach ($daftarKategori as $kategori)
                    <option value="{{ $kategori }}" @selected($kategoriDipilih === $kategori)>{{ $kategori }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Cari</button>
            @if ($kataKunci !== '' || $kategoriDipilih !== '')
                <a href="{{ route('laptop.index') }}" class="btn">Reset Pencarian</a>
            @endif
        </form>
    </div>

    <div class="card">
        <p class="muted" style="margin-top: 0;">
            Menampilkan <strong>{{ count($daftarLaptop) }}</strong> dari {{ $totalSemuaLaptop }} data laptop.
        </p>

        @if (count($daftarLaptop) === 0)
            <div class="empty-state">
                @if ($totalSemuaLaptop === 0)
                    <p><strong>Belum ada data laptop.</strong></p>
                    <p>Silakan tambahkan data laptop pertama Anda.</p>
                    <a href="{{ route('laptop.create') }}" class="btn btn-primary">+ Tambah Laptop</a>
                @else
                    <p><strong>Data laptop tidak ditemukan.</strong></p>
                    <p>Tidak ada laptop yang cocok dengan pencarian Anda.</p>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Laptop</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th class="text-right">Jumlah</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarLaptop as $laptop)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $laptop->getKode() }}</strong></td>
                                <td>{{ $laptop->getNama() }}</td>
                                <td><span class="badge badge-cat">{{ $laptop->getKategori() }}</span></td>
                                <td>{{ $laptop->getLokasi() }}</td>
                                <td class="text-right">{{ $laptop->getJumlah() }} unit</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($laptop->getKondisi()) }}">{{ $laptop->getKondisi() }}</span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('laptop.show', $laptop->getKode()) }}" class="btn btn-sm">Detail</a>
                                        <a href="{{ route('laptop.edit', $laptop->getKode()) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" action="{{ route('laptop.destroy', $laptop->getKode()) }}"
                                              onsubmit="return confirm(@js('Yakin ingin menghapus laptop '.$laptop->getKode().' - '.$laptop->getNama().'?'));">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('laptop.reset') }}"
          onsubmit="return confirm('Kembalikan semua data ke data contoh awal?');" style="text-align: right;">
        @csrf
        <button type="submit" class="btn btn-sm">Kembalikan Data Contoh</button>
    </form>
@endsection
