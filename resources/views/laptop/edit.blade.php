@extends('layouts.app')

@section('title', 'Edit Laptop')
@section('subtitle', 'Perbarui data laptop '.$laptop->getKode().' - '.$laptop->getNama().'.')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('laptop.update', $laptop->getKode()) }}">
            @csrf
            @method('PUT')

            @include('laptop._form', ['laptop' => $laptop])

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('laptop.show', $laptop->getKode()) }}" class="btn">Batal</a>
            </div>
        </form>
    </div>
@endsection
