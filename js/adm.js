function CarregarUsers() {
    fetch('ajax.php?users')
        .then(response => response.text())
        .then(data => {
            document.getElementById('users').innerHTML = data;
        })
        .catch(error => {
            console.error('Erro ao carregar os usuários:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    CarregarUsers();
});

function del(item) {
    const id = item.getAttribute('id');
    const userName = item.getAttribute('data-username');
    const alertDiv = document.querySelector('.alert');

    const confirmMessage = `Tem certeza de que deseja excluir o usuário "${userName}"? Essa ação não pode ser desfeita.`;

    if (confirm(confirmMessage)) {
        fetch('ajax.php?delUser=' + id)
            .then(response => response.text())
            .then(retorno => {
                CarregarUsers();

                // Exibir mensagem de sucesso
                if (alertDiv) {
                    alertDiv.innerHTML = `Usuário "${userName}" excluído com sucesso!`;
                    alertDiv.classList.remove('error');
                    alertDiv.classList.add('success');
                    alertDiv.style.display = "block";
                }
            })
            .catch(error => {
                console.error('Erro ao excluir o usuário:', error);

                // Exibir mensagem de erro
                if (alertDiv) {
                    alertDiv.innerHTML = `Erro ao excluir o usuário!`;
                    alertDiv.classList.remove('success');
                    alertDiv.classList.add('error');
                    alertDiv.style.display = "block";
                }
            })
            .finally(() => {
                // Ocultar a mensagem após 5 segundos
                if (alertDiv) {
                    setTimeout(() => {
                        alertDiv.style.display = "none";
                    }, 5000);
                }
            });
    }
}
