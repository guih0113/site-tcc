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

function del(item){
    id = item.getAttribute('id');

    fetch('ajax.php?delUser='+id)

    .then(response => {
        return response.text();
    })

    .then(retorno => {
        alert(retorno);
        CarregarUsers("");
    });
}