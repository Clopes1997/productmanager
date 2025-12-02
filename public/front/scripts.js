$(document).ready(function () {
    loadInitialData();

    function loadInitialData() {
        fetchProdutos();
        fetchMarcas();
        fetchCidades();
    }

    function fetchProdutos() {
        $.ajax({
            url: getApiUrl('api/produtos'),
            method: 'GET',
            success: renderProdutos,
            error: handleError('Erro ao buscar produtos')
        });
    }

    function renderProdutos(produtos) {
        $('#produto-list').empty(); // Limpa dados anteriores
        let total = 0;
        let count = 0;

        // Verificar se os dados foram recebidos
        console.log('Produtos recebidos:', produtos);

        produtos.forEach(produto => {
            const marcaNome = produto.marca ? produto.marca.nome : 'Marca desconhecida';
            const cidadeNome = produto.cidade ? produto.cidade.nome : 'Cidade desconhecida';

            $('#produto-list').append(createProdutoRow(produto, marcaNome, cidadeNome));

            // Atualiza total e contagem para média e soma
            total += parseFloat(produto.valor); // Converte o valor para número
            count++;
        });

        // Atualiza a média e soma na tabela
        updateMediaSoma(total, count);
    }

    function createProdutoRow(produto, marcaNome, cidadeNome) {
        return `
            <tr>
                <td>${produto.nome}</td>
                <td>R$ ${parseFloat(produto.valor).toFixed(2)}</td>
                <td>${produto.estoque}</td>
                <td>${marcaNome}</td>
                <td>${cidadeNome}</td>
                <td>
                    <button class="edit btn btn-warning" data-id="${produto.id}" data-bs-toggle="modal" data-bs-target="#editProdutoModal">Editar</button>
                    <button class="delete btn btn-danger" data-id="${produto.id}">Excluir</button>
                </td>
            </tr>
        `;
    }

    function updateMediaSoma(total, count) {
        if (count > 0) {
            const media = total / count;
            $('#resultado').text(`Soma Total: R$ ${total.toFixed(2)}, Média de Valores: R$ ${media.toFixed(2)}`);
        } else {
            $('#resultado').text('Nenhum produto encontrado.');
        }
    }

    function fetchMarcas() {
        $.ajax({
            url: getApiUrl('api/marcas'),
            method: 'GET',
            success: function (data) {
                data.forEach(marca => {
                    $('#marca_id').append(`<option value="${marca.id}">${marca.nome}</option>`);
                    $('#novaMarca_id').append(`<option value="${marca.id}">${marca.nome}</option>`);
                    $('#editMarca_id').append(`<option value="${marca.id}">${marca.nome}</option>`); // Popula o dropdown de marcas do modal de edição
                });
            },
            error: handleError('Erro ao buscar marcas')
        });
    }

    function fetchCidades() {
        $.ajax({
            url: getApiUrl('api/cidades'),
            method: 'GET',
            success: function (data) {
                const addDropdown = $('#add_cidade_id').empty(); // Limpa o dropdown do modal de adicionar
                const editDropdown = $('#editCidade_id').empty(); // Limpa o dropdown do modal de edição
                const cidadeId = $('#cidade_id').empty(); // Limpa o dropdown de filtro de cidade

                // Adiciona a opção vazia a todos os dropdowns
                addDropdown.append(`<option value="">Selecione uma cidade</option>`);
                editDropdown.append(`<option value="">Selecione uma cidade</option>`);
                cidadeId.append(`<option value="">Selecione uma cidade</option>`);

                // Adiciona as cidades recebidas
                data.forEach(cidade => {
                    addDropdown.append(`<option value="${cidade.id}">${cidade.nome}</option>`);
                    editDropdown.append(`<option value="${cidade.id}">${cidade.nome}</option>`);
                    cidadeId.append(`<option value="${cidade.id}">${cidade.nome}</option>`);
                });
            },
            error: handleError('Erro ao buscar cidades')
        });
    }

    // Ao abrir o modal de adicionar, limpa os campos e preenche o dropdown
    $('#addProdutoModal').on('show.bs.modal', function () {
        clearAddModal(); // Limpa os campos do modal de adicionar
        fetchCidades(); // Preenche o dropdown de cidades
    });

    // Ao abrir o modal de editar, preenche os campos e o dropdown
    $('#editProdutoModal').on('show.bs.modal', function () {
        fetchCidades(); // Preenche o dropdown de cidades
    });

    // Ao abrir o modal de editar, preenche os campos
    $(document).on('click', '.edit', function () {
        const id = $(this).data('id'); // Obtém o ID do produto
        $.ajax({
            url: getApiUrl(`api/produtos/${id}`),
            method: 'GET',
            success: function (produto) {
                $('#editNome').val(produto.nome);
                $('#editValor').val(produto.valor);
                $('#editEstoque').val(produto.estoque);
                $('#editMarca_id').val(produto.marca_id);
                $('#editCidade_id').val(produto.cidade_id);
                $('#editProdutoModal').modal('show'); // Abre o modal de edição
                $('#editProdutoForm').data('id', id); // Armazena o ID no formulário para uso posterior
            },
            error: handleError('Erro ao buscar produto')
        });
    });

// Adicionar novo produto
$('#addProdutoForm').submit(function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const produtoData = {
        nome: $('#nome').val(),
        valor: parseFloat($('#valor').val()), // Certifique-se de que o valor é um número
        estoque: parseInt($('#estoque').val()), // Certifique-se de que o estoque é um número inteiro
        marca_id: $('#novaMarca_id').val(),
        cidade_id: $('#add_cidade_id').val() // Usa o novo ID
    };

    // Verifica se todos os campos estão preenchidos
	console.log(produtoData);
    if (validateProdutoData(produtoData)) {
        createProduto(produtoData); // Cria novo produto
    }
});

function createProduto(produtoData) {
    $.ajax({
        url: getApiUrl('api/produtos'),
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(produtoData),
        success: function (response) {
            console.log('Produto adicionado com sucesso:', response);
            fetchProdutos(); // Atualiza a lista de produtos
            $('#addProdutoModal').modal('hide'); // Fecha o modal
        },
        error: function (err) {
            console.error('Erro ao adicionar produto', err);
            alert('Erro ao adicionar produto. Verifique os dados e tente novamente.');
        }
    });
}

// Salvar alterações ao editar produto
$('#editProdutoForm').submit(function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const id = $('#editProdutoForm').data('id'); // Obtém o ID armazenado no formulário
    const produtoData = {
        nome: $('#editNome').val(),
        valor: parseFloat($('#editValor').val()), // Certifique-se de que o valor é um número
        estoque: parseInt($('#editEstoque').val()), // Certifique-se de que o estoque é um número inteiro
        marca_id: $('#editMarca_id').val(),
        cidade_id: $('#editCidade_id').val() // Usa o ID correto
    };

    // Verifica se todos os campos estão preenchidos
    if (validateProdutoData(produtoData)) {
        updateProduto(id, produtoData); // Atualiza o produto
    }
});

// Função para validar dados do produto
function validateProdutoData(produtoData) {
    if (!produtoData.nome || !produtoData.valor || !produtoData.marca_id || !produtoData.cidade_id) {
        alert('Todos os campos devem ser preenchidos.');
        return false;
    }
    return true;
}

function updateProduto(id, produtoData) {
    $.ajax({
        url: getApiUrl(`api/produtos/${id}`),
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(produtoData),
        success: function (response) {
            console.log('Produto atualizado com sucesso:', response);
            fetchProdutos(); // Atualiza a lista de produtos
            $('#editProdutoModal').modal('hide'); // Fecha o modal
        },
        error: function (err) {
            console.error('Erro ao atualizar produto', err);
            alert('Erro ao atualizar produto. Verifique os dados e tente novamente.');
        }
    });
}

    // Excluir produto
    $(document).on('click', '.delete', function () {
        const id = $(this).data('id');
        $.ajax({
            url: getApiUrl(`api/produtos/${id}`),
            method: 'GET',
            success: function (produto) {
                if (produto.estoque > 0) {
                    // Se o estoque for maior que 0
                    alert('Não é possível excluir o produto. O estoque deve ser 0 para poder deletá-lo.');
                } else {
                    // Se o estoque for 0
                    $.ajax({
                        url: getApiUrl(`api/produtos/${id}`),
                        method: 'DELETE',
                        success: function () {
                            fetchProdutos();
                        },
                        error: handleError('Erro ao excluir produto')
                    });
                }
            },
            error: handleError('Erro ao buscar produto para exclusão')
        });
    });

    // Remover filtros
    $(document).on('click', '#removerFiltros', function() {
        $('#minValor').val('');
        $('#maxValor').val(''); // Limpa o valor máximo
        $('#cidade_id').val(''); // Limpa a seleção do dropdown de cidades
        $('#marca_id').val(''); // Limpa a seleção do dropdown de marcas
        fetchProdutos(); // Busca todos os produtos sem filtros
        fetchCidades(); // Recarrega o dropdown de cidades
    });

    // Filtrar produtos por valor
    $(document).on('click', '#filtrarValor', function() {
        const min = $('#minValor').val();
        const max = $('#maxValor').val();

        $.ajax({
            url: getApiUrl('api/produtos/filter'),
            method: 'GET',
            data: { min: min, max: max },
            success: renderProdutos, // Atualiza a tabela com os produtos filtrados
            error: handleError('Erro ao filtrar produtos por valor')
        });
    });

    // Filtrar produtos por cidade
    $(document).on('click', '#filtrarCidade', function() {
        const cidadeId = $('#cidade_id').val(); // Certifique-se de que o ID do dropdown de cidade é correto
    
        $.ajax({
            url: getApiUrl('api/produtos/filter/cidade'), // Certifique-se de que essa rota está implementada na sua API
            method: 'GET',
            data: { cidade_id: cidadeId },
            success: renderProdutos, // Atualiza a tabela com os produtos filtrados
            error: handleError('Erro ao filtrar produtos por cidade')
        });
    });

    // Filtrar produtos por marca
    $(document).on('click', '#filtrarMarca', function() {
        const marcaId = $('#marca_id').val(); // Supondo que o ID do dropdown de marca é 'marca_id'

        $.ajax({
            url: getApiUrl('api/produtos/filter/marca'), // Certifique-se de que essa rota está implementada na sua API
            method: 'GET',
            data: { marca_id: marcaId },
            success: renderProdutos, // Atualiza a tabela com os produtos filtrados
            error: handleError('Erro ao filtrar produtos por marca')
        });
    });

    // Função para lidar com erros
    function handleError(message) {
        return function (err) {
            console.error(message, err);
        };
    }

    // Função para limpar o modal de adição
    function clearAddModal() {
        $('#addProdutoForm')[0].reset(); // Reseta todos os campos do formulário
    }
});
