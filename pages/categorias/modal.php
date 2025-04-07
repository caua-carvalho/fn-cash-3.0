<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCadastrarCategoriaLabel">Cadastrar Nova Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="categorias.php" method="POST">
                <input type="hidden" name="acao" value="cadastrarCategoria">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomeCategoria">Nome da Categoria</label>
                        <input type="text" class="form-control" id="nomeCategoria" name="nomeCategoria" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoCategoria">Tipo</label>
                        <select class="form-control" id="tipoCategoria" name="tipoCategoria" required>
                            <option value="Receita">Receita</option>
                            <option value="Despesa">Despesa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descricaoCategoria">Descrição</label>
                        <textarea class="form-control" id="descricaoCategoria" name="descricaoCategoria" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-labelledby="editarCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="categorias.php" method="POST">
                <input type="hidden" name="acao" value="editarCategoria">
                <div class="modal-body">
                    <input type="hidden" id="editarCategoriaId" name="categoriaId">
                    <div class="form-group">
                        <label for="editarNomeCategoria">Nome da Categoria</label>
                        <input type="text" class="form-control" id="editarNomeCategoria" name="nomeCategoria" required>
                    </div>
                    <div class="form-group">
                        <label for="editarTipoCategoria">Tipo</label>
                        <select class="form-control" id="editarTipoCategoria" name="tipoCategoria" required>
                            <option value="Receita">Receita</option>
                            <option value="Despesa">Despesa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editarDescricaoCategoria">Descrição</label>
                        <textarea class="form-control" id="editarDescricaoCategoria" name="descricaoCategoria" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editarStatusCategoria">Status</label>
                        <select class="form-control" id="editarStatusCategoria" name="statusCategoria" required>
                            <option value="true">Ativa</option>
                            <option value="false">Inativa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editarButtons = document.querySelectorAll('[data-target="#editarCategoriaModal"]');
        editarButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');
                const tipo = this.getAttribute('data-tipo');
                const descricao = this.getAttribute('data-descricao');
                const status = this.getAttribute('data-status');

                document.getElementById('editarCategoriaId').value = id;
                document.getElementById('editarNomeCategoria').value = nome;
                document.getElementById('editarTipoCategoria').value = tipo;
                document.getElementById('editarDescricaoCategoria').value = descricao;

                // Converte o valor de status para o formato esperado pelo select
                document.getElementById('editarStatusCategoria').value = status === 'true' ? 'true' : 'false';
            });
        });
    });
</script>