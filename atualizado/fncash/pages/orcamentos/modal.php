<div class="modal fade" id="orcamentoModal" tabindex="-1" aria-labelledby="orcamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Formulário para lidar com a criação de um novo orçamento -->
            <form action="orcamento.php" method="POST">
                <!-- Campo oculto para especificar a ação do formulário -->
                <input type="hidden" name="acao" value="cadastrarOrcamento">
                <div class="modal-header">
                    <!-- Título do modal -->
                    <h5 class="modal-title" id="orcamentoModalLabel">Cadastrar Novo Orçamento</h5>
                    <!-- Botão para fechar o modal -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Grupo de formulário para inserir o valor planejado -->
                    <div class="form-group">
                        <label for="titulo">Titulo</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <!-- Grupo de formulário para selecionar uma categoria -->
                    <div class="form-group">
                        <label for="categoriaId">Categoria</label>
                        <select class="form-control" id="categoriaId" name="categoriaId" required>
                            <option value="">Selecione uma categoria</option>
                            <?php
                            // Buscar e exibir categorias dinamicamente
                            $categorias = obterCategorias();
                            foreach ($categorias as $categoria) {
                                echo "<option value='" . $categoria['ID_Categoria'] . "'>" . htmlspecialchars($categoria['Nome']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Grupo de formulário para inserir o valor planejado -->
                    <div class="form-group">
                        <label for="valorPlanejado">Valor Planejado</label>
                        <input type="number" class="form-control" id="valorPlanejado" name="valorPlanejado" step="0.01" required>
                    </div>
                    <!-- Grupo de formulário para especificar o período de início e fim -->
                    <div class="form-group">
                        <div class="d-flex">
                            <div class="w-50 mr-2">
                                <label for="periodoInicio">Início</label>
                                <input type="date" class="form-control" id="periodoInicio" name="periodoInicio" required>
                            </div>
                            <div class="w-50">
                                <label for="periodoFim">Fim</label>
                                <input type="date" class="form-control" id="periodoFim" name="periodoFim" required>
                            </div>
                        </div>
                    </div>
                    <!-- Grupo de formulário para selecionar o status -->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botão para cancelar e fechar o modal -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <!-- Botão para enviar o formulário e salvar -->
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>