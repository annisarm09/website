@extends('admin.layouts.base4')

@section('content')
<form action="{{ route('pesan.kirim') }}" method="POST">
    @csrf
    <input type="text" name="nama" placeholder="Nama Lengkap" required>
    <input type="text" name="kontak" placeholder="Email / No. WhatsApp" required>
    <textarea name="pesan" rows="5" placeholder="Pesan Anda..." required></textarea>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-paper-plane"></i> Kirim Pesan
    </button>
</form>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@endsection
