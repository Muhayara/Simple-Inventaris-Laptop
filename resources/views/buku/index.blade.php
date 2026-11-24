@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="card">
        <h1 style="margin-top: 0;">Daftar Buku</h1>
        <ul>
            @foreach ($daftarBuku as $buku)
                <li style="margin-bottom: 8px;">
                    <a href="{{ route('buku.show', $buku['id']) }}">
                        {{ $buku['judul'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
