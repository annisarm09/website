//tombol shortcut ctrl + alt + l untuk membuka modal login
document.addEventListener('keydown', function(e) {
    // Shortcut: Ctrl + Alt + L
    if (e.ctrlKey && e.altKey && e.key.toLowerCase() === 'l') {
        e.preventDefault();

        const loginBtn = document.getElementById('adminLoginBtn');

        if (loginBtn) {
            // Jika sedang sembunyi, munculkan. Jika sedang muncul, sembunyikan.
            if (loginBtn.style.display === 'none') {
                loginBtn.style.display = 'block';
                // Beri sedikit delay agar animasi opacity jalan
                setTimeout(() => {
                    loginBtn.style.opacity = '1';
                }, 10);
                console.log("Pintu rahasia terbuka!");
            } else {
                loginBtn.style.opacity = '0';
                setTimeout(() => {
                    loginBtn.style.display = 'none';
                }, 500);
            }
        }
    }
});
