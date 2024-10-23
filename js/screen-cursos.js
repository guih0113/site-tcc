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

function CarregarCursosScreen() {
    fetch('ajax.php?cursos')
        .then(response => response.text())
        .then(data => {
            document.getElementById('course-container').innerHTML = data;
        })
        .catch(error => {
            console.error('Erro ao carregar os cursos:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    CarregarCursosScreen();
});
