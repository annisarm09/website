@extends('admin.layouts.base1')

@section('content')
@forelse($berita as $item)
<article class="news-card fade-in" data-category="{{ $item->kategori }}">
    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/default.jpg') }}"
         alt="{{ $item->judul_berita }}" class="news-image">
    <div class="news-content">
        <div class="news-meta">
            <i class="fas fa-calendar"></i>
            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
            &nbsp;|&nbsp;
            <i class="fas fa-tags"></i> {{ ucfirst($item->kategori) }}
        </div>
        <h3>{{ $item->judul_berita }}</h3>
        <p>{{ Str::limit($item->isi_berita, 150) }}</p>
        <a href="{{ route('berita.show', $item->slug) }}" class="btn btn-primary">
            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</article>
@empty
<p style="text-align:center; color: #888;">Belum ada berita.</p>
@endforelse

@if(method_exists($berita, 'links'))
    <div style="text-align:center; margin: 2rem 0;">
        {{ $berita->links() }}
    </div>
@endif
@endsection
