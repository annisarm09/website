@extends('admin.layouts.baseshow')

@section('content')
<div class="container py-5" style="max-width: 900px;">

    @if(!isset($berita))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> Berita tidak ditemukan.
        </div>
    @else
    <article class="card shadow-sm border-0 overflow-hidden" style="border-radius: 16px;">

        {{-- Foto berita --}}
        @if($berita->foto)
            <img src="{{ asset('storage/' . $berita->foto) }}"
                 class="img-fluid w-100"
                 alt="{{ $berita->judul_berita }}"
                 style="max-height: 480px; object-fit: cover; border-radius: 16px 16px 0 0;">
        @else
            {{-- Placeholder jika tidak ada foto --}}
            <div style="height: 200px; background: linear-gradient(135deg, #1a5c2e, #2e7d4f);
                        display: flex; align-items: center; justify-content: center;
                        border-radius: 16px 16px 0 0;">
                <i class="fas fa-newspaper" style="font-size: 4rem; color: rgba(255,255,255,.4);"></i>
            </div>
        @endif

        <div class="card-body p-4 p-md-5">

            {{-- Judul --}}
            <h1 class="fw-bold mb-3" style="color: var(--hijau-tua, #1a5c2e); font-size: clamp(1.4rem, 3vw, 2rem); font-family: 'Amiri', serif;">
                {{ $berita->judul_berita }}
            </h1>

            {{-- Meta: tanggal & kategori --}}
            <div class="d-flex flex-wrap gap-3 text-muted mb-4" style="font-size: .92rem;">
                <span>
                    <i class="fas fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}
                </span>
                <span>
                    <i class="fas fa-tag"></i>
                    <span style="
                        background: #d1fae5; color: #065f46;
                        padding: .15rem .65rem; border-radius: 999px;
                        font-size: .8rem; font-weight: 700;">
                        {{ ucfirst($berita->kategori) }}
                    </span>
                </span>
            </div>

            <hr style="border-color: #e5e7eb;">

            {{-- Isi berita --}}
            <div style="line-height: 1.9; font-size: 1.05rem; text-align: justify; color: #374151; margin-top: 1.5rem;">
                {!! nl2br(e($berita->isi_berita)) !!}
            </div>

        </div>
    </article>

    {{-- Navigasi bawah --}}
    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('berita') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Berita Lainnya
        </a>
    </div>
    @endif

</div>
@endsection
