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

    const confirmMessage = `Tem certeza de que deseja excluir o usuário "${userName}"? Essa ação não pode ser desfeita.`;

    if (confirm(confirmMessage)) {
        fetch('ajax.php?delUser=' + id)
            .then(response => response.text())
            .then(retorno => {
                CarregarUsers();
                alert('Usuário excluído com sucesso!');
            })
            .catch(error => {
                console.error('Erro ao excluir o usuário:', error);
            });
    }
}
