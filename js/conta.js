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

document.getElementById('update_info').addEventListener('click', function(event) {
    var usernameInput = document.getElementById('username');
    var emailInput = document.getElementById('email');
    if (usernameInput.value.length < 7 || !validateEmail(emailInput.value)) {
        event.preventDefault();
    }
});

function validateEmail(email) {
    var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(email);
}




