{{--
    Partial form yang dipakai bersama oleh create.blade.php dan edit.blade.php.
    Variabel: $laptop (null saat tambah), $daftarKategori, $daftarLokasi, $daftarKondisi.
    old() dipakai agar input tetap terisi ketika validasi gagal; pada form edit
    nilai awalnya diambil dari data laptop lama.
--}}
@if ($errors->any())
    <div class="alert alert-error">
        <strong>Data belum dapat disimpan. Periksa kembali isian berikut:</strong>
        <ul>
            @foreach ($errors->all() as $pesanError)
                <li>{{ $pesanError }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-grid">
    <div class="form-group">
        <label for="kode">Kode Laptop</label>
        @if ($laptop)
            <input type="text" id="kode" value="{{ $laptop->getKode() }}" readonly>
            <span class="hint">Kode tidak dapat diubah karena menjadi kunci data.</span>
        @else
            <input type="text" id="kode" name="kode" value="{{ old('kode') }}" maxlength="20"
                   placeholder="Contoh: LPT-B01" class="@error('kode') is-invalid @enderror" required>
            @error('kode')
                <span class="error-text">{{ $message }}</span>
            @enderror
        @endif
    </div>

    <div class="form-group">
        <label for="nama">Nama Laptop</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $laptop?->getNama()) }}" maxlength="100"
               placeholder="Contoh: Dell Latitude 7440" class="@error('nama') is-invalid @enderror" required>
        @error('nama')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori" class="@error('kategori') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($daftarKategori as $kategori)
                <option value="{{ $kategori }}" @selected(old('kategori', $laptop?->getKategori()) === $kategori)>{{ $kategori }}</option>
            @endforeach
        </select>
        @error('kategori')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="lokasi">Lokasi Penyimpanan</label>
        <select id="lokasi" name="lokasi" class="@error('lokasi') is-invalid @enderror" required>
            <option value="">-- Pilih Lokasi --</option>
            @foreach ($daftarLokasi as $lokasi)
                <option value="{{ $lokasi }}" @selected(old('lokasi', $laptop?->getLokasi()) === $lokasi)>{{ $lokasi }}</option>
            @endforeach
        </select>
        @error('lokasi')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="jumlah">Jumlah (unit)</label>
        <input type="number" id="jumlah" name="jumlah" min="0" max="9999"
               value="{{ old('jumlah', $laptop?->getJumlah()) }}" class="@error('jumlah') is-invalid @enderror" required>
        @error('jumlah')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="harga">Harga Satuan (Rp)</label>
        <input type="number" id="harga" name="harga" min="0" step="1000"
               value="{{ old('harga', $laptop?->getHarga()) }}" placeholder="Contoh: 15000000"
               class="@error('harga') is-invalid @enderror" required>
        @error('harga')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group full">
        <label>Kondisi</label>
        <div class="radio-group">
            @foreach ($daftarKondisi as $kondisi)
                <label>
                    <input type="radio" name="kondisi" value="{{ $kondisi }}"
                           @checked(old('kondisi', $laptop?->getKondisi() ?? 'Baru') === $kondisi) required>
                    {{ $kondisi }}
                </label>
            @endforeach
        </div>
        @error('kondisi')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group full">
        <label for="spesifikasi">Spesifikasi <span class="muted">(opsional)</span></label>
        <textarea id="spesifikasi" name="spesifikasi" rows="3" maxlength="255"
                  placeholder="Contoh: Intel Core i5-1335U, 16GB RAM, 512GB SSD"
                  class="@error('spesifikasi') is-invalid @enderror">{{ old('spesifikasi', $laptop?->getSpesifikasi()) }}</textarea>
        @error('spesifikasi')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>
</div>
