<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona todos os botões que abrem o modal de edição
        const editarButtons = document.querySelectorAll('[data-target="#editarCategoriaModal"]');
        editarButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém os atributos de dados do botão clicado
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');
                const tipo = this.getAttribute('data-tipo');
                const descricao = this.getAttribute('data-descricao');
                const status = this.getAttribute('data-status');

                // Preenche os campos do modal com os valores obtidos
                document.getElementById('editarCategoriaId').value = id;
                document.getElementById('editarNomeCategoria').value = nome;
                document.getElementById('editarTipoCategoria').value = tipo;
                document.getElementById('editarDescricaoCategoria').value = descricao;

                // Converte o valor de status para o formato esperado pelo select
                document.getElementById('editarStatusCategoria').value = status === 'true' ? 'true' : 'false';
            });
        });

        // Seleciona todos os botões que abrem o modal de exclusão
        const excluirButtons = document.querySelectorAll('[data-target="#excluirCategoriaModal"]');
        excluirButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém o ID da categoria do botão clicado
                const id = this.getAttribute('data-id');
                // Preenche o campo oculto do modal com o ID da categoria
                document.getElementById('excluirCategoriaId').value = id;
            });
        });
    });
</script>