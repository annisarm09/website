        function filterNews(category, btn) {
            const buttons = document.querySelectorAll('.btn[onclick^="filterNews"]');
            const newsCards = document.querySelectorAll('.news-card');
            buttons.forEach(b => { b.classList.remove('btn-primary','active'); b.classList.add('btn-secondary'); });
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-primary','active');
            newsCards.forEach(card => {
                card.style.display = (category === 'all' || card.dataset.category === category) ? 'flex' : 'none';
            });
        }
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
