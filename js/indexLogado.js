const profile = document.querySelector('#profile'); // Seleciona a imagem de perfil
const dropdown = document.querySelector('.dropdown');

profile.addEventListener("click", function(event) {
    // Impede o evento de clique de se propagar para o documento
    event.stopPropagation();
    // Alterna a classe 'show' para controle de visibilidade
    dropdown.classList.toggle('show');
});

// Adiciona um event listener ao documento para esconder o dropdown se clicar fora
document.addEventListener("click", function(event) {
    // Verifica se o clique foi fora do dropdown e da imagem de perfil
    if (!dropdown.contains(event.target) && event.target !== profile) {
        dropdown.classList.remove('show');
    }
});

function CarregarCursos() {
    fetch('ajax.php?carrossel')
        .then(response => response.text())
        .then(data => {
            document.getElementById('carrossel').innerHTML = data;
        })
        .catch(error => {
            console.error('Erro ao carregar os cursos:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    CarregarCursos();
});

// Carrossel
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.getElementsByClassName('curso-card'); // Usando getElementsByClassName
    console.log(cards);
    let currentIndex = 1;

    function updateCarousel() {
        // Usando loop for
        for (let index = 0; index < cards.length; index++) {
            const card = cards[index];
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
        }
    }

    document.querySelector('.next').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % cards.length;
        updateCarousel();
    });

    document.querySelector('.prev').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + cards.length) % cards.length;
        updateCarousel();
    });

    // Chama a rotação inicial
    updateCarousel();

    // Adiciona uma rotação inicial automática de 0,1 segundo para evitar delay inicial
    setTimeout(() => {
        currentIndex = (currentIndex + 1) % cards.length;
        updateCarousel();

        // Depois inicia o intervalo de 4 segundos
        setInterval(() => {
            currentIndex = (currentIndex + 1) % cards.length;
            updateCarousel();
        }, 4000);
    }, 100);
});

