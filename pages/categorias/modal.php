<div class="modal fade" id="modalCadastrarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCadastrarCategoriaLabel">Cadastrar Nova Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="salvar_categoria.php" method="POST">
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