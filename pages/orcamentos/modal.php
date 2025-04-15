<div class="modal fade" id="orcamentoModal" tabindex="-1" aria-labelledby="orcamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="orcamentos.php" method="POST">
                <input type="hidden" name="acao" value="cadastrarOrcamento">
                <div class="modal-header">
                    <h5 class="modal-title" id="orcamentoModalLabel">Cadastrar Novo Orçamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoriaId">Categoria</label>
                        <select class="form-control" id="categoriaId" name="categoriaId" required>
                            <?php
                            $categorias = obterCategorias();
                            foreach ($categorias as $categoria) {
                                echo "<option value='" . $categoria['ID_Categoria'] . "'>" . htmlspecialchars($categoria['Nome']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="valorPlanejado">Valor Planejado</label>
                        <input type="number" class="form-control" id="valorPlanejado" name="valorPlanejado" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="periodo">Período</label>
                        <input type="text" class="form-control" id="periodo" name="periodo" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>