<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para cadastrar nova categoria -->
                <h5 class="modal-title" id="modalCadastrarCategoriaLabel">Cadastrar Nova Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para cadastrar nova categoria -->
            <form action="categorias.php" method="POST">
                <input type="hidden" name="acao" value="cadastrarCategoria">
                <div class="modal-body">
                    <!-- Campo para o nome da categoria -->
                    <div class="form-group">
                        <label for="nomeCategoria">Nome da Categoria</label>
                        <input type="text" class="form-control" id="nomeCategoria" name="nomeCategoria" required>
                    </div>
                    <!-- Campo para selecionar o tipo da categoria -->
                    <div class="form-group">
                        <label for="tipoCategoria">Tipo</label>
                        <select class="form-control" id="tipoCategoria" name="tipoCategoria" required>
                            <option value="Receita">Receita</option>
                            <option value="Despesa">Despesa</option>
                        </select>
                    </div>
                    <!-- Campo para a descrição da categoria -->
                    <div class="form-group">
                        <label for="descricaoCategoria">Descrição</label>
                        <textarea class="form-control" id="descricaoCategoria" name="descricaoCategoria" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botões para cancelar ou salvar -->
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
                <!-- Título do modal para editar categoria -->
                <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para editar categoria -->
            <form action="categorias.php" method="POST">
                <input type="hidden" name="acao" value="editarCategoria">
                <div class="modal-body">
                    <!-- Campo oculto para armazenar o ID da categoria -->
                    <input type="hidden" id="editarCategoriaId" name="categoriaId">
                    <!-- Campo para o nome da categoria -->
                    <div class="form-group">
                        <label for="editarNomeCategoria">Nome da Categoria</label>
                        <input type="text" class="form-control" id="editarNomeCategoria" name="nomeCategoria" required>
                    </div>
                    <!-- Campo para selecionar o tipo da categoria -->
                    <div class="form-group">
                        <label for="editarTipoCategoria">Tipo</label>
                        <select class="form-control" id="editarTipoCategoria" name="tipoCategoria" required>
                            <option value="Receita">Receita</option>
                            <option value="Despesa">Despesa</option>
                        </select>
                    </div>
                    <!-- Campo para a descrição da categoria -->
                    <div class="form-group">
                        <label for="editarDescricaoCategoria">Descrição</label>
                        <textarea class="form-control" id="editarDescricaoCategoria" name="descricaoCategoria" rows="3"></textarea>
                    </div>
                    <!-- Campo para selecionar o status da categoria -->
                    <div class="form-group">
                        <label for="editarStatusCategoria">Status</label>
                        <select class="form-control" id="editarStatusCategoria" name="statusCategoria" required>
                            <option value="true">Ativa</option>
                            <option value="false">Inativa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botões para cancelar ou salvar alterações -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="excluirCategoriaModal" tabindex="-1" aria-labelledby="excluirCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para confirmação de exclusão -->
                <h5 class="modal-title" id="excluirCategoriaModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para exclusão de categoria -->
            <form action="categorias.php" method="POST">
                <input type="hidden" name="acao" value="excluirCategoria">
                <input type="hidden" id="excluirCategoriaId" name="categoriaId">
                <div class="modal-body">
                    <!-- Mensagem de confirmação -->
                    <p>Tem certeza de que deseja excluir esta categoria? Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <!-- Botões para cancelar ou confirmar exclusão -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

