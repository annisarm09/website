        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, observerOptions);
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

        function openLoginModal() {
            const modal = document.getElementById("loginModal");
            if (modal) { modal.classList.remove("hidden"); modal.style.display = "flex"; }
        }

        function closeLoginModal() {
            const modal = document.getElementById("loginModal");
            if (modal) modal.style.display = "none";
        }

        window.onclick = function(e) {
            let modal = document.getElementById("loginModal");
            if (e.target === modal) modal.style.display = "none";
        }

        setTimeout(function() {
            let popup = document.getElementById('logoutPopup');
            if (popup) { popup.classList.add('hide'); setTimeout(() => popup.remove(), 500); }
        }, 3000);

        const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');
        if (hamburger) {
            hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
        }
