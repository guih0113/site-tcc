document.addEventListener("DOMContentLoaded", function () {
    const selectCurso = document.getElementById("course-select");
    const selectModulo = document.getElementById("module-select");
    const selectAula = document.getElementById("class-select");
    const nomeInput = document.getElementById("course-name");
    const descricaoInput = document.getElementById("course-description");
    const categoriaInput = document.getElementById("course-category");
    const nomeAulaInput = document.getElementById("class-name");
    const conteudoAulaInput = document.getElementById("class-content");
    
    // Inicialmente, desabilitar os campos
    nomeInput.disabled = true;
    nomeAulaInput.disabled = true;
    conteudoAulaInput.disabled = true;
    
    // Habilitar inputs quando uma opção válida for selecionada
    function enableInputsIfValid() {
        const isModuleSelected = selectModulo.value !== "";
        const isClassSelected = selectAula.value !== "";
    
        // Habilitar 'nomeInput' (Nome do Curso) apenas com módulo selecionado
        nomeInput.disabled = !isModuleSelected;
    
        // Habilitar 'nomeAulaInput' e 'conteudoAulaInput' quando módulo e aula estão selecionados
        nomeAulaInput.disabled = !(isModuleSelected && isClassSelected);
        conteudoAulaInput.disabled = !(isModuleSelected && isClassSelected);
    }
    
    // Adicionar eventos de mudança nos selects para habilitar os inputs
    selectModulo.addEventListener('change', enableInputsIfValid);
    selectAula.addEventListener('change', enableInputsIfValid);
    


    // Limpar o campo de nome do módulo ao carregar a página
    nomeInput.value = '';

    // Carregar dados do curso ao selecionar um curso no dropdown
    selectCurso.addEventListener("change", function () {
        const cursoId = this.value;

        if (cursoId) {
            // Carregar dados do curso
            fetch(`?id_curso=${cursoId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        nomeInput.value = ''; // Limpar nome do módulo
                        descricaoInput.value = data.descricao_curso;
                        categoriaInput.value = data.icone_curso;
                    } else {
                        alert("Curso não encontrado.");
                    }
                })
                .catch(error => console.error('Erro ao buscar dados do curso:', error));

            // Carregar módulos do curso selecionado
            fetch(`?id_curso_modulos=${cursoId}`)
                .then(response => response.json())
                .then(modulos => {
                    if (!modulos.error) {
                        // Limpar opções de módulos
                        selectModulo.innerHTML = '<option value="">Selecione um módulo</option>';
                        modulos.forEach(modulo => {
                            const option = document.createElement('option');
                            option.value = modulo.id_modulo;
                            option.textContent = modulo.nome_modulo;
                            selectModulo.appendChild(option);
                        });
                    } else {
                        alert("Nenhum módulo encontrado para este curso.");
                    }
                })
                .catch(error => console.error('Erro ao buscar módulos:', error));
        } else {
            nomeInput.value = ''; // Limpar nome do módulo se nenhum curso for selecionado
            descricaoInput.value = '';
            categoriaInput.value = '';
            selectModulo.innerHTML = '<option value="">Selecione um módulo</option>'; // Limpar módulos
            selectAula.innerHTML = '<option value="">Selecione uma aula</option>'; // Limpar aulas
        }
    });

    // Adicionar evento para exibir o nome do módulo no input quando selecionado
    selectModulo.addEventListener("change", function () {
        const moduloId = this.value;

        if (moduloId) {
            // Buscar nome do módulo selecionado
            fetch(`?id_modulo=${moduloId}`)
                .then(response => response.json())
                .then(modulo => {
                    if (!modulo.error) {
                        nomeInput.value = modulo.nome_modulo; // Exibir nome do módulo no input
                    } else {
                        alert("Módulo não encontrado.");
                    }
                })
                .catch(error => console.error('Erro ao buscar nome do módulo:', error));

            // Carregar aulas do módulo selecionado
            fetch(`?id_modulo_aulas=${moduloId}`)
                .then(response => response.json())
                .then(aulas => {
                    if (!aulas.error) {
                        // Limpar opções de aulas
                        selectAula.innerHTML = '<option value="">Selecione uma aula</option>';
                        aulas.forEach(aula => {
                            const option = document.createElement('option');
                            option.value = aula.id_aula;
                            option.textContent = aula.nome_aula;
                            selectAula.appendChild(option);
                        });
                    } else {
                        alert("Nenhuma aula encontrada para este módulo.");
                    }
                })
                .catch(error => console.error('Erro ao buscar aulas:', error));
        } else {
            nomeInput.value = ''; // Limpar nome do módulo se nenhum módulo for selecionado
            selectAula.innerHTML = '<option value="">Selecione uma aula</option>'; // Limpar aulas
        }
    });

    // Carregar dados da aula ao selecionar uma aula
    selectAula.addEventListener("change", function () {
        const aulaId = this.value;

        if (aulaId) {
            fetch(`?id_aula=${aulaId}`)
                .then(response => response.json())
                .then(aula => {
                    if (!aula.error) {
                        nomeAulaInput.value = aula.nome_aula; // Exibir nome da aula no input
                        conteudoAulaInput.value = aula.conteudo_aula; // Exibir conteúdo da aula
                    } else {
                        alert("Aula não encontrada.");
                    }
                })
                .catch(error => console.error('Erro ao buscar nome da aula:', error));
        } else {
            nomeAulaInput.value = ''; // Limpar o campo de nome da aula
            conteudoAulaInput.value = ''; // Limpar o campo de conteúdo da aula
        }
    });

    // Função do botão "Cancelar"
    document.querySelector(".btn-secondary").addEventListener("click", function () {
        window.location.href = "editarConteudo.php";
    });

    // Excluir Módulo
    document.querySelector("button[name='delete-module']").addEventListener("click", function () {
        const moduleId = document.querySelector("#module-select").value;
        if (!moduleId) {
            alert("Selecione um módulo para excluir.");
            return;
        }

        if (confirm("Tem certeza de que deseja excluir este módulo?")) {
            fetch('editarConteudo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `delete-module=true&id_modulo=${moduleId}`
            })
                .then(response => response.json())
                .then(data => {
                    var alertDiv = document.querySelector(".alert");
                    alertDiv.style.display = "block";
                    alertDiv.innerHTML = data.message;
                    alertDiv.classList.toggle("success", data.status === "success");
                    alertDiv.classList.toggle("error", data.status === "error");
                })
                .catch(error => console.error('Erro ao excluir o módulo:', error));
        }
    });

    // Excluir Aula
    document.querySelector("button[name='delete-class']").addEventListener("click", function () {
        const classId = document.querySelector("#class-select").value;
        if (!classId) {
            alert("Selecione uma aula para excluir.");
            return;
        }

        if (confirm("Tem certeza de que deseja excluir esta aula?")) {
            fetch('editarConteudo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `delete-class=true&id_aula=${classId}`
            })
                .then(response => response.json())
                .then(data => {
                    var alertDiv = document.querySelector(".alert");
                    alertDiv.style.display = "block";
                    alertDiv.innerHTML = data.message;
                    alertDiv.classList.toggle("success", data.status === "success");
                    alertDiv.classList.toggle("error", data.status === "error");
                })
                .catch(error => console.error('Erro ao excluir a aula:', error));
        }
    });

});
