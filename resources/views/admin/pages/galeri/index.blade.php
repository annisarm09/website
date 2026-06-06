@extends('admin.layouts.base2')
@section('content')
<div class="gallery-grid" id="galleryContainer">
    @forelse($galeri as $item)
    <a href="{{ asset('storage/' . $item->src) }}"
       class="gallery-item fade-in"
       data-lightbox="{{ $item->kategori }}"
       data-title="{{ $item->kegiatan }}"
       data-category="{{ $item->kategori }}">
        <img src="{{ asset('storage/' . $item->src) }}" alt="{{ $item->kegiatan }}" loading="lazy">
        <div class="gallery-overlay">
            <h4>{{ $item->kegiatan }}</h4>
            <p>{{ $item->timestamp ?? $item->created_at->format('d M Y') }}</p>
        </div>
    </a>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 4rem;">
        <p>Galeri masih kosong.</p>
    </div>
    @endforelse
</div>

@endsection
