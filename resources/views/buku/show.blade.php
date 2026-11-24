@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <div class="card">
        <h1 style="margin-top: 0;">Detail Buku</h1>
        <p>ID buku: {{ $id }}</p>
        <a href="{{ route('buku.index') }}" class="btn">
            Kembali ke daftar
        </a>
    </div>
@endsection
