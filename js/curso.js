//Ícone profile
const profile = document.querySelector('#profile'); // Seleciona a imagem de perfil
const dropdown = document.querySelector('.dropdown');

profile.addEventListener("click", function (event) {
    // Impede o evento de clique de se propagar para o documento
    event.stopPropagation();
    // Alterna a classe 'show' para controle de visibilidade
    dropdown.classList.toggle('show');
});

// Adiciona um event listener ao documento para esconder o dropdown se clicar fora
document.addEventListener("click", function (event) {
    // Verifica se o clique foi fora do dropdown e da imagem de perfil
    if (!dropdown.contains(event.target) && event.target !== profile) {
        dropdown.classList.remove('show');
    }
});

//Div sidebar
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', () => {
            const dropdownContent = item.nextElementSibling;
            dropdownContent.classList.toggle('active');
            if (dropdownContent.classList.contains('active')) {
                dropdownContent.style.height = dropdownContent.scrollHeight + 'px';
            } else {
                dropdownContent.style.height = '0';
            }
        });
    });
});

//transição sidebar
const sidebar = document.querySelector('.sidebar');
const toggleButton = document.querySelector('.toggle-button');

toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});

//alternando a seta
const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

dropdownToggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
        toggle.classList.toggle('active');
    });
});


//conteúdo das aulas sendo mostrados abaixo do video
const aulas = document.querySelectorAll('.aula-link');
// Adiciona um evento de clique a cada botão
aulas.forEach(aula => {
    aula.addEventListener('click', function (event) {
        // Evita o comportamento padrão do link
        event.preventDefault();

        // Obtém o nome do curso e do módulo usando data attributes
        const tituloAula = this.textContent;
        const moduloAula = this.closest('.module').getAttribute('data-modulo');

        // Exibe o nome e o módulo do curso nos elementos correspondentes
        document.getElementById('titulo-aula').textContent = `${moduloAula} | ${tituloAula}`;
    });
});