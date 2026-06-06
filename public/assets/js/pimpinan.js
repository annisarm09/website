/*DATA BERITA untuk modal preview (aman dari karakter spesial)*/
    // ── Tab switching ──
    function showTab(name, el) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        if (el) el.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = '{{ session("active_tab", "approval") }}';
        const el = document.querySelector(`.nav-item[onclick="showTab('${activeTab}', this)"]`);
        if (el) showTab(activeTab, el);
    });

    // ── Modal Preview ──
    let currentPreviewId    = null;
    let currentPreviewJudul = '';

    function bukaPreviewById(id) {
        const d = beritaData[id];
        if (!d) return;

        currentPreviewId    = d.id;
        currentPreviewJudul = d.judul;

        document.getElementById('previewJudul').textContent    = d.judul;
        document.getElementById('previewIsi').innerText        = d.isi;
        document.getElementById('previewKategori').textContent = ucfirstJS(d.kategori);
        document.getElementById('previewTanggal').textContent  = d.tanggal;

        const fotoEl = document.getElementById('previewFoto');
        if (d.foto) {
            fotoEl.src = d.foto;
            fotoEl.style.display = 'block';
        } else {
            fotoEl.style.display = 'none';
        }

        // Set action form approve di footer modal
        document.getElementById('formApprovePreview').action = '/pimpinan/approve/' + d.id;

        document.getElementById('modalPreview').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    // Fallback wrapper (sudah tidak pakai inline json)
    function bukaPreview(id, judul, isi, kategori, tanggal, foto) {
        bukaPreviewById(id);
    }

    function tutupPreview() {
        document.getElementById('modalPreview').classList.remove('show');
        document.body.style.overflow = '';
    }

    // ── Modal Tolak ──
    function bukaModalTolak(id, judul) {
        document.getElementById('modalTolakJudul').textContent = judul;
        document.getElementById('formTolak').action = '/pimpinan/reject/' + id;
        document.getElementById('modalTolak').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function bukaModalTolakById(id, judul) {
        bukaModalTolak(id, judul);
    }

    function tutupModalTolak() {
        document.getElementById('modalTolak').classList.remove('show');
        document.body.style.overflow = '';
    }

    // Klik di luar modal untuk menutup
    document.getElementById('modalPreview').addEventListener('click', function(e) {
        if (e.target === this) tutupPreview();
    });
    document.getElementById('modalTolak').addEventListener('click', function(e) {
        if (e.target === this) tutupModalTolak();
    });

    // Helper ucfirst JS
    function ucfirstJS(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    }

    // ── Auto-hide alert ──
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            a.style.transition = 'opacity .5s';
            a.style.opacity = '0';
            setTimeout(() => a.remove(), 500);
        });
    }, 5000);
