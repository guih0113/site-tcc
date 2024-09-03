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

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.nav-list a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.tab-content').forEach(section => {
                section.style.display = 'none';
            });
            document.querySelector(link.getAttribute('href')).style.display = 'block';
            document.querySelectorAll('.nav-list a').forEach(link => link.classList.remove('active'));
            link.classList.add('active');
        });
    });

    // Exibir a primeira seção por padrão
    document.querySelector('#account-general').style.display = 'block';
});


