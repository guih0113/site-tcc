document.addEventListener('DOMContentLoaded', () => {
    //Ícone profile
    const profile = document.querySelector('#profile'); // Seleciona a imagem de perfil
    const dropdown = document.querySelector('.dropdown');

    profile.addEventListener("click", function (event) {
        event.stopPropagation(); // Impede o evento de clique de se propagar para o documento
        dropdown.classList.toggle('show'); // Alterna a classe 'show' para controle de visibilidade
    });

    // Adiciona um event listener ao documento para esconder o dropdown se clicar fora
    document.addEventListener("click", function (event) {
        if (!dropdown.contains(event.target) && event.target !== profile) {
            dropdown.classList.remove('show');
        }
    });

    // Adicionar listeners aos links das aulas
    const titleAula = document.querySelectorAll('.aula-link');
    titleAula.forEach((aula) => {
        aula.addEventListener('click', (event) => {
            console.log("teste");
            event.preventDefault();

            // Pegar o título da aula
            const tituloAula = aula.textContent;

            // Pegar o elemento do módulo pai
            const moduloElement = aula.closest('.module');

            // Pegar o nome do módulo a partir do h2 dentro do módulo atual
            const nomeModulo = moduloElement.querySelector('h2').textContent;

            // Atualizar o conteúdo do h3#titulo-aula com o nome do módulo e da aula
            document.getElementById('titulo-aula').innerHTML = `<span id="modulo-aula">${nomeModulo}</span> | Aula: ${tituloAula}`;
        });
    });
});
