document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('current-year'))
        document.getElementById('current-year').textContent = new Date().getFullYear();

    if (document.getElementById('show-mobile-menu')) {
        document.getElementById('show-mobile-menu').addEventListener('click', () => {
            if (document.querySelector('.site-navigation'))
                document.querySelector('.site-navigation').style.left = '0';

            if (document.getElementById('hide-mobile-menu'))
                document.getElementById('hide-mobile-menu').style.display = 'flex';
        });
    }

    if (document.getElementById('hide-mobile-menu')) {
        document.getElementById('hide-mobile-menu').addEventListener('click', () => {
            if (document.querySelector('.site-navigation'))
                document.querySelector('.site-navigation').style.left = '-100%';

            document.getElementById('hide-mobile-menu').style.display = 'none';
        });
    }

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const lazySection = entry.target;
                const bgUrl = lazySection.getAttribute('data-bg');
                lazySection.style.backgroundImage = bgUrl; 

                observer.unobserve(lazySection);
            }
        });
    }, {
        rootMargin: '200px',
        threshold: 0.1 
    });

    const lazySections = document.querySelectorAll('.lazy');
    lazySections.forEach(section => {
        observer.observe(section);
    });
});