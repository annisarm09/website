    function showTab(name, el) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        if (el) el.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = '{{ session("active_tab", "beranda") }}';
        const el = document.querySelector(`.nav-item[onclick="showTab('${activeTab}', this)"]`);
        if (el) showTab(activeTab, el);
    });

    function previewImg(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            a.style.transition = 'opacity .5s';
            a.style.opacity = '0';
            setTimeout(() => a.remove(), 500);
        });
    }, 5000);
