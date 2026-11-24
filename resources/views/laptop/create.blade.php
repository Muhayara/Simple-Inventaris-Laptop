@extends('layouts.app')

@section('title', 'Tambah Laptop')
@section('subtitle', 'Isi form berikut untuk menambahkan data laptop baru.')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('laptop.store') }}">
            @csrf

            @include('laptop._form', ['laptop' => null])

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('laptop.index') }}" class="btn">Batal</a>
            </div>
        </form>
    </div>
@endsection
