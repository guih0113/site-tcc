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
//ícone profile

//Div sidebar
const dropdownModules = document.querySelectorAll('.dropdown-toggle');
document.addEventListener('DOMContentLoaded', () => {
    dropdownModules.forEach((dropdownModule) => {
        const dropdownContent = dropdownModule.nextElementSibling;
        
        dropdownModule.addEventListener('click', () => {
            dropdownContent.classList.toggle('active');
            if (dropdownContent.classList.contains('active')) {
                dropdownContent.style.height = dropdownContent.scrollHeight + 'px';
            } else {
                dropdownContent.style.height = '0';
            }
        });
    });
});


// Add event listeners to the aula links
document.querySelectorAll('.aula-link').forEach((aula) => {
    aula.addEventListener('click', (event) => {
        event.preventDefault();
        const tituloAula = aula.textContent;
        const moduloAula = aula.closest('.module').getAttribute('data-modulo');
        document.getElementById('titulo-aula').innerHTML = `${moduloAula} | ${tituloAula}`;
    });
});

