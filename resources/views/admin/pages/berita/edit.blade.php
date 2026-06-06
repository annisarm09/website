@extends('admin.layouts.baseshow')

@section('content')
<div class="container py-5" style="padding-top: 120px !important;">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <h2 class="mb-4" style="color: var(--hijau-tua);">
                <i class="fas fa-edit"></i> Edit Berita
            </h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">JUDUL BERITA</label>
                    <input type="text"
                           name="judul_berita"
                           class="form-control @error('judul_berita') is-invalid @enderror"
                           value="{{ old('judul_berita', $berita->judul_berita) }}"
                           required>
                    @error('judul_berita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">KATEGORI</label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="kegiatan"   {{ old('kategori', $berita->kategori) == 'kegiatan'   ? 'selected' : '' }}>Kegiatan</option>
                        <option value="pengumuman" {{ old('kategori', $berita->kategori) == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="prestasi"   {{ old('kategori', $berita->kategori) == 'prestasi'   ? 'selected' : '' }}>Prestasi</option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">TANGGAL</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal', $berita->tanggal) }}"
                           required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">GAMBAR BERITA</label>

                    {{-- Tampilkan foto lama jika ada --}}
                    @if($berita->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $berita->foto) }}"
                             alt="Foto saat ini"
                             style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
                        <p class="text-muted small mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                    </div>
                    @endif

                    <div class="border rounded p-4 text-center mb-2"
                         style="border-style: dashed; border-color: #aaa; cursor: pointer;"
                         onclick="document.getElementById('inputFoto').click()">
                        <i class="fas fa-cloud-upload-alt fa-2x text-success"></i>
                        <p class="mb-0 mt-2">Klik untuk ganti gambar (opsional)</p>
                        <small class="text-muted">JPG, PNG, WEBP – Maks. 2MB</small>
                    </div>
                    <input type="file"
                           name="foto"
                           id="inputFoto"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp"
                           style="display: none;"
                           onchange="previewFoto(this)">
                    <div id="previewContainer" class="mt-2" style="display: none;">
                        <img id="previewImg" src="#" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                    </div>
                </div>

                {{-- Isi Berita --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">ISI BERITA</label>
                    <textarea name="isi_berita"
                              class="form-control @error('isi_berita') is-invalid @enderror"
                              rows="10"
                              required>{{ old('isi_berita', $berita->isi_berita) }}</textarea>
                    @error('isi_berita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">STATUS</label>
                    <select name="status" class="form-select">
                        <option value="pending"   {{ old('status', $berita->status) == 'pending'   ? 'selected' : '' }}>Pending (Menunggu Persetujuan)</option>
                        <option value="draft"     {{ old('status', $berita->status) == 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $berita->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="rejected"  {{ old('status', $berita->status) == 'rejected'  ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary px-4 py-2">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewContainer').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
