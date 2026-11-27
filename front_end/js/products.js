document.addEventListener("DOMContentLoaded", () => {

    // ELEMENTOS DA PÁGINA

    const modal = document.getElementById("productModal");
    const form = document.getElementById("productForm");
    const popup = document.getElementById("popupMensagem");
    const tabelaProdutos = document.getElementById("recentProducts");

    const campoId = form?.querySelector('[name="id_produto"]');
    const tituloModal = document.getElementById("productModalTitle");
    const botaoSalvar = form?.querySelector('button[type="submit"]');


    // NAVEGAÇÃO ENTRE AS PÁGINAS

    document.querySelectorAll('[data-go="products"]').forEach(button =>
        button.addEventListener("click", () => location.href = "produtos.php")
    );

    // POP-UP DE MENSAGENS

    let tempoPopup;

    const mostrarPopup = (mensagem, tipo) => {
        if (!popup) return;

        popup.textContent = mensagem;
        popup.className = "popup-mensagem mostrar " + tipo;

        clearTimeout(tempoPopup);

        tempoPopup = setTimeout(() => {
            popup.classList.remove("mostrar");
        }, 3000);
    };


    // ABRIR E FECHAR MODAL

    const openModal = () => {
        if (!modal) return;

        modal.classList.add("open");
        modal.setAttribute("aria-hidden", "false");

        const primeiroCampo = modal.querySelector('input:not([type="hidden"]), select');

        if (primeiroCampo) primeiroCampo.focus();
    };

    const closeModal = () => {
        if (!modal) return;

        modal.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");
    };


    // ABRIR FORMULÁRIO PARA NOVO PRODUTO

    const novoProduto = () => {

        if (!form) return;

        form.reset();

        if (campoId) {
            campoId.value = "";
        }

        if (tituloModal) {
            tituloModal.textContent = "Novo produto";
        }

        if (botaoSalvar) {
            botaoSalvar.textContent = "Salvar produto";
        }

        openModal();
    };

    document.getElementById("addProductBtn")?.addEventListener("click", novoProduto);
    document.getElementById("quickAdd")?.addEventListener("click", novoProduto);

    modal?.querySelectorAll("[data-close-modal]").forEach(button =>
        button.addEventListener("click", closeModal)
    );

    modal?.addEventListener("click", event => {
        if (event.target === modal) closeModal();
    });

    document.addEventListener("keydown", event => {
        if (event.key === "Escape" && modal?.classList.contains("open")) {
            closeModal();
        }
    });


    // PESQUISA E FILTROS DOS PRODUTOS

    const pesquisa = document.getElementById("productSearch");
    const filtroCategoria = document.getElementById("categoryFilter");
    const filtroEstoque = document.getElementById("stockFilter");

    if (pesquisa && filtroCategoria && filtroEstoque && tabelaProdutos) {

        const linhas = Array.from(tabelaProdutos.querySelectorAll("tr"));

        // Carregar categorias automaticamente
        const categorias = [...new Set(
            linhas.map(linha => linha.cells[2]?.textContent.trim())
                .filter(categoria => categoria !== "")
        )].sort((a, b) => a.localeCompare(b, "pt-BR"));

        categorias.forEach(categoria => {

            const opcao = document.createElement("option");

            opcao.value = categoria;
            opcao.textContent = categoria;

            filtroCategoria.appendChild(opcao);
        });

        // Mensagem quando nenhum produto for encontrado
        const linhaSemResultados = document.createElement("tr");

        linhaSemResultados.hidden = true;

        linhaSemResultados.innerHTML = `
            <td colspan="9">Nenhum produto encontrado.</td>
        `;

        tabelaProdutos.appendChild(linhaSemResultados);

        // Aplicar pesquisa e filtros
        const filtrarProdutos = () => {

            const textoPesquisa = pesquisa.value.toLowerCase().trim();
            const categoriaSelecionada = filtroCategoria.value;
            const estoqueSelecionado = filtroEstoque.value;

            let encontrados = 0;

            linhas.forEach(linha => {

                const nome = linha.cells[0]?.textContent.toLowerCase() || "";
                const codigo = linha.cells[1]?.textContent.toLowerCase() || "";
                const categoria = linha.cells[2]?.textContent.trim() || "";
                const estoque = linha.dataset.stock || "";

                const correspondePesquisa =
                    nome.includes(textoPesquisa) ||
                    codigo.includes(textoPesquisa);

                const correspondeCategoria =
                    categoriaSelecionada === "" ||
                    categoria === categoriaSelecionada;

                const correspondeEstoque =
                    estoqueSelecionado === "" ||
                    estoque === estoqueSelecionado;

                const mostrar =
                    correspondePesquisa &&
                    correspondeCategoria &&
                    correspondeEstoque;

                linha.hidden = !mostrar;

                if (mostrar) {
                    encontrados++;
                }

            });

            linhaSemResultados.hidden = encontrados > 0;
        };

        pesquisa.addEventListener("input", filtrarProdutos);
        filtroCategoria.addEventListener("change", filtrarProdutos);
        filtroEstoque.addEventListener("change", filtrarProdutos);

    }


    // EDITAR PRODUTOS

    tabelaProdutos?.addEventListener("click", event => {

        const botaoEditar = event.target.closest(".btn-edit-product");

        if (!botaoEditar) return;

        const linha = botaoEditar.closest("tr");

        if (!linha || !form || !campoId) return;

        const colunas = linha.cells;

        // Identificar produto pelo ID
        campoId.value = linha.dataset.id;

        // Preencher os campos com os dados atuais
        form.elements["name"].value = colunas[0].textContent.trim();
        form.elements["code"].value = colunas[1].textContent.trim();
        form.elements["category"].value = colunas[2].textContent.trim();
        form.elements["descripition"].value = colunas[3].textContent.trim();
        form.elements["price"].value = colunas[4].textContent.trim();
        form.elements["quantity"].value = colunas[5].textContent.trim();
        form.elements["min"].value = linha.dataset.min;

        // Alterar título e botão
        if (tituloModal) {
            tituloModal.textContent = "Editar produto";
        }

        if (botaoSalvar) {
            botaoSalvar.textContent = "Salvar alterações";
        }

        openModal();

    });


    // CADASTRAR E ATUALIZAR PRODUTOS

    if (form) {

        form.addEventListener("submit", async event => {

            event.preventDefault();

            const dados = new FormData(form);

            const botao = form.querySelector('button[type="submit"]');

            // Verificar se é cadastro ou edição
            const idProduto = campoId?.value.trim() || "";

            const estaEditando = idProduto !== "";

            // Escolher o arquivo PHP conforme a operação
            const endereco = estaEditando
                ? "../back_end/processos/editar_produtos.php"
                : "../back_end/processos/registros_produtos.php";

            try {

                if (botao) {
                    botao.disabled = true;
                    botao.textContent = "Salvando...";
                }

                const resposta = await fetch(endereco, {
                    method: "POST",
                    body: dados
                });

                const resultado = await resposta.json();

                if (!resposta.ok) {
                    throw new Error("Erro na comunicação com o servidor.");
                }

                // Operação realizada com sucesso
                if (resultado.sucesso) {

                    mostrarPopup(
                        estaEditando
                            ? "Produto atualizado com sucesso!"
                            : "Produto cadastrado com sucesso!",
                        "sucesso"
                    );

                    form.reset();

                    if (campoId) {
                        campoId.value = "";
                    }

                    closeModal();

                    // Atualizar a tabela
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                } else {

                    mostrarPopup(resultado.mensagem, "erro");

                }

            } catch (erro) {

                console.error("Erro ao salvar produto:", erro);

                mostrarPopup("Erro ao salvar produto!", "erro");

            } finally {

                if (botao) {
                    botao.disabled = false;

                    botao.textContent = campoId?.value
                        ? "Salvar alterações"
                        : "Salvar produto";
                }

            }

        });

    }

    // EXCLUIR PRODUTOS

    tabelaProdutos?.addEventListener("click", async event => {

        const botaoExcluir = event.target.closest(".btn-delete-product");

        if (!botaoExcluir) return;

        const linha = botaoExcluir.closest("tr");

        if (!linha) return;

        const idProduto = linha.dataset.id;

        const confirmar = confirm("Deseja realmente excluir este produto?");

        if (!confirmar) return;

        const dados = new FormData();
        dados.append("id_produto", idProduto);

        try {

            botaoExcluir.disabled = true;
            botaoExcluir.textContent = "Excluindo...";

            const resposta = await fetch(
                "../back_end/processos/excluir_produtos.php",
                {
                    method: "POST",
                    body: dados
                }
            );

            const resultado = await resposta.json();

            if (!resposta.ok) {
                throw new Error("Erro na comunicação com o servidor.");
            }

            if (resultado.sucesso) {

                mostrarPopup("Produto excluído com sucesso!", "sucesso");

                setTimeout(() => {
                    location.reload();
                }, 1200);

            } else {

                mostrarPopup(resultado.mensagem, "erro");

                botaoExcluir.disabled = false;
                botaoExcluir.textContent = "Excluir";

            }

        } catch (erro) {

            console.error("Erro ao excluir produto:", erro);

            mostrarPopup("Erro ao excluir produto!", "erro");

            botaoExcluir.disabled = false;
            botaoExcluir.textContent = "Excluir";

        }

    });

});