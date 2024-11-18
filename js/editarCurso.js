document.addEventListener("DOMContentLoaded", function() {
    const selectCurso = document.getElementById("course-select");
    const nomeInput = document.getElementById("course-name");
    const descricaoInput = document.getElementById("course-description");
    const categoriaInput = document.getElementById("course-category");

    nomeInput.disabled = true;
    descricaoInput.disabled = true;
    categoriaInput.disabled = true;

    function enableInputsIfValid() {
        const courseSelect = selectCurso.value !== "";

        nomeInput.disabled = !courseSelect;
        descricaoInput.disabled = !courseSelect;
        categoriaInput.disabled = !courseSelect;
    }

    selectCurso.addEventListener('change', enableInputsIfValid);

    // Carregar dados do curso ao selecionar um curso no dropdown
    selectCurso.addEventListener("change", function() {
        const cursoId = this.value;

        if (cursoId) {
            fetch(`?id_curso=${cursoId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        nomeInput.value = data.nome_curso;
                        descricaoInput.value = data.descricao_curso;
                        categoriaInput.value = data.icone_curso;
                    } else {
                        alert("Curso não encontrado.");
                    }
                })
                .catch(error => console.error('Erro ao buscar dados do curso:', error));
        } else {
            nomeInput.value = '';
            descricaoInput.value = '';
            categoriaInput.value = '';
        }
    });

    // Função do botão "Cancelar"
    document.querySelector(".btn-secondary").addEventListener("click", function() {
        window.location.href = "editarCurso.php";
    });

    // Função do botão "Excluir Curso"
    document.querySelector(".btn-danger").addEventListener("click", function() {
        const cursoId = selectCurso.value;
        if (!cursoId) {
            alert("Selecione um curso para excluir.");
            return;
        }

        if (confirm("Tem certeza de que deseja excluir este curso?")) {
            fetch('editarCurso.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `delete_course=true&id_curso=${cursoId}`
            })
            .then(response => response.json())
            .then(data => {
                var alertDiv = document.querySelector(".alert"); // Acessa a div de alerta
                alertDiv.style.display = "block"; // Exibe a div de alerta
                if (data.status === "success") {
                    alertDiv.innerHTML = data.message; // Exibe a mensagem de sucesso
                    alertDiv.classList.remove("error");
                    alertDiv.classList.add("success");
                } else {
                    alertDiv.innerHTML = data.message; // Exibe a mensagem de erro
                    alertDiv.classList.remove("success");
                    alertDiv.classList.add("error");
                }
            })
            .catch(error => console.error('Erro ao excluir o curso:', error));
        }
    });
});