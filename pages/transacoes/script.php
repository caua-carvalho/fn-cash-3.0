<script>
    // funcao para forms dinamico de cadastro
    document.addEventListener('DOMContentLoaded', function () {
        const tipoTransacao = document.getElementById('tipoTransacao');
        const contaDestinatariaGroup = document.getElementById('contaDestinataria').parentElement;

        // Exibe ou oculta o campo "Conta Destinatária" com base no tipo de transação
        tipoTransacao.addEventListener('change', function () {
            if (this.value === 'Transferência') {
                contaDestinatariaGroup.style.display = 'block';
                document.getElementById('contaDestinataria').required = true;
            } else {
                contaDestinatariaGroup.style.display = 'none';
                document.getElementById('contaDestinataria').required = false;
            }
        });

        // Inicializa o estado do campo ao carregar a página
        if (tipoTransacao.value !== 'Transferência') {
            contaDestinatariaGroup.style.display = 'none';
            document.getElementById('contaDestinataria').required = false;
        }
    });

    // funcao para forms dinamico de editar
    document.addEventListener('DOMContentLoaded', function () {
        const tipoTransacao = document.getElementById('editarTipoTransacao');
        const contaDestinatariaGroup = document.getElementById('editarContaDestinataria').parentElement;

        // Exibe ou oculta o campo "Conta Destinatária" com base no tipo de transação
        tipoTransacao.addEventListener('change', function () {
            if (this.value === 'Transferência') {
                contaDestinatariaGroup.style.display = 'block';
                document.getElementById('editarContaDestinataria').required = true;
            } else {
                contaDestinatariaGroup.style.display = 'none';
                document.getElementById('editarContaDestinataria').required = false;
            }
        });

        // Inicializa o estado do campo ao carregar a página
        if (tipoTransacao.value === 'Transferência') {
            contaDestinatariaGroup.style.display = 'block';
            document.getElementById('editarContaDestinataria').required = true;
        } else {
            contaDestinatariaGroup.style.display = 'none';
            document.getElementById('editarContaDestinataria').required = false;
        }
    });
    
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona todos os botões que abrem o modal de edição
        const editarButtons = document.querySelectorAll('[data-target="#editarTransacaoModal"]');
        editarButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém os atributos de dados do botão clicado
                const id = this.getAttribute('data-id');
                const titulo = this.getAttribute('data-titulo');
                const descricao = this.getAttribute('data-descricao');
                const valor = this.getAttribute('data-valor');
                const data = this.getAttribute('data-data');
                const tipo = this.getAttribute('data-tipo');
                const status = this.getAttribute('data-status');
                const contaRemetenteId = this.getAttribute('data-conta-remetente-id');
                const contaDestinatariaId = this.getAttribute('data-conta-destinataria-id');

                // Preenche os campos do modal com os valores obtidos
                document.getElementById('editarTransacaoId').value = id;
                document.getElementById('editarTituloTransacao').value = titulo;
                document.getElementById('editarDescricaoTransacao').value = descricao;
                document.getElementById('editarValorTransacao').value = valor;
                document.getElementById('editarDataTransacao').value = data;
                document.getElementById('editarTipoTransacao').value = tipo;
                document.getElementById('editarStatusTransacao').value = status;

                // Seleciona as contas remetente e destinatária
                document.getElementById('editarContaRemetente').value = contaRemetenteId;
                document.getElementById('editarContaDestinataria').value = contaDestinatariaId;

                // Exibe ou oculta o campo "Conta Destinatária" com base no tipo de transação
                const contaDestinatariaGroup = document.getElementById('editarContaDestinataria').parentElement;
                if (tipo === 'Transferência') {
                    contaDestinatariaGroup.style.display = 'block';
                    document.getElementById('editarContaDestinataria').required = true;
                } else {
                    contaDestinatariaGroup.style.display = 'none';
                    document.getElementById('editarContaDestinataria').required = false;
                }
            });
        });

        // Seleciona todos os botões que abrem o modal de exclusão
        const excluirButtons = document.querySelectorAll('[data-target="#excluirTransacaoModal"]');
        excluirButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém o ID da transação do botão clicado
                const id = this.getAttribute('data-id');
                // Preenche o campo oculto do modal com o ID da transação
                document.getElementById('excluirTransacaoId').value = id;
            });
        });
    });
</script>