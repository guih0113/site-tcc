document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.curso-card');
    let currentIndex = 1;

    function updateCarousel() {
        cards.forEach((card, index) => {
            card.classList.remove('active', 'left', 'right');
            card.style.order = '';

            if (index === currentIndex) {
                card.classList.add('active');
                card.style.order = 1;
            } else if (index === (currentIndex - 1 + cards.length) % cards.length) {
                card.classList.add('left');
                card.style.order = 0;
            } else if (index === (currentIndex + 1) % cards.length) {
                card.classList.add('right');
                card.style.order = 2;
            } else {
                card.style.order = 3;
            }
        });
    }

    document.querySelector('.next').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % cards.length;
        updateCarousel();
    });

    document.querySelector('.prev').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + cards.length) % cards.length;
        updateCarousel();
    });

    // Adiciona a rotação automática a cada 3 segundos
    setInterval(() => {
        currentIndex = (currentIndex + 1) % cards.length;
        updateCarousel();
    }, 5000); // 3000 milissegundos = 3 segundos

    updateCarousel();
});