document.addEventListener("DOMContentLoaded", function () {
    const selectCurso = document.getElementById("course-select");
    const selectModulo = document.getElementById("module-select");
    const nomeAulaInput = document.getElementById("class-name");
    const conteudoAulaInput = document.getElementById("class-content");

    // Inicialmente, desabilitar os campos
    nomeAulaInput.disabled = true;
    conteudoAulaInput.disabled = true;

    // Habilitar inputs quando uma opção válida for selecionada
    function enableInputsIfValid() {
        const isModuleSelected = selectModulo.value !== "";

        // Habilitar 'nomeAulaInput' e 'conteudoAulaInput' quando um módulo for selecionado
        nomeAulaInput.disabled = !isModuleSelected;
        conteudoAulaInput.disabled = !isModuleSelected;
    }

    // Adicionar eventos de mudança nos selects para habilitar os inputs
    selectCurso.addEventListener("change", function () {
        const cursoId = selectCurso.value;

        // Recarregar os módulos ao selecionar um curso
        if (cursoId) {
            fetch(`adicionarConteudo.php?id_curso_modulos=${cursoId}`)
                .then((response) => response.json())
                .then((modulos) => {
                    selectModulo.innerHTML = "<option value=''>Selecione um módulo</option>";
                    modulos.forEach((modulo) => {
                        const option = document.createElement("option");
                        option.value = modulo.id_modulo;
                        option.textContent = modulo.nome_modulo;
                        selectModulo.appendChild(option);
                    });

                    // Atualizar os estados dos inputs
                    enableInputsIfValid();
                })
                .catch((error) => {
                    console.error("Erro ao carregar módulos:", error);
                });
        } else {
            // Limpar os módulos se nenhum curso for selecionado
            selectModulo.innerHTML = "<option value=''>Selecione um módulo</option>";
            enableInputsIfValid();
        }
    });

    selectModulo.addEventListener("change", enableInputsIfValid);

    // Definir o valor inicial e travar os 4 primeiros caracteres
    conteudoAulaInput.value = "img/";

    // Adicionar um evento para monitorar alterações no campo
    conteudoAulaInput.addEventListener("input", function () {
        // Verificar se os 4 primeiros caracteres foram alterados
        if (!conteudoAulaInput.value.startsWith("img/")) {
            // Restaurar o prefixo caso tenha sido alterado
            conteudoAulaInput.value = "img/" + conteudoAulaInput.value.slice(4);
        }
    });

    // Adicionar evento de foco para garantir que o cursor não fique antes de "img/"
    conteudoAulaInput.addEventListener("focus", function () {
        // Colocar o cursor no final do texto ao focar
        const length = conteudoAulaInput.value.length;
        conteudoAulaInput.setSelectionRange(length, length);
    });

    // Adicionar evento de seleção para impedir que o prefixo seja selecionado
    conteudoAulaInput.addEventListener("select", function (e) {
        // Impedir que o início do texto ("img/") seja selecionado
        const start = conteudoAulaInput.selectionStart;
        if (start < 4) {
            conteudoAulaInput.setSelectionRange(4, conteudoAulaInput.selectionEnd);
        }
    });


    selectCurso.addEventListener("change", function () {
        const courseId = this.value;

        // Limpar o dropdown de módulos
        selectModulo.innerHTML = '<option value="">Selecione um módulo</option>';

        if (courseId) {
            // Fazer a requisição AJAX para buscar os módulos do curso selecionado
            fetch(`editarConteudo.php?id_curso_modulos=${courseId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        data.forEach((modulo) => {
                            const option = document.createElement("option");
                            option.value = modulo.id_modulo;
                            option.textContent = modulo.nome_modulo;
                            selectModulo.appendChild(option);
                        });
                    }
                })
                .catch((error) => {
                    console.error("Erro ao carregar módulos:", error);
                });
        }
    });
});