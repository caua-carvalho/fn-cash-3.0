<!-- Modal de Cadastro de Orçamento -->
<div class="modal fade" id="orcamentoModal" tabindex="-1" aria-labelledby="orcamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orcamentoModalLabel">Novo Orçamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="orcamento.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="cadastrarOrcamento">
                
                <div class="modal-body">
                    <div class="tab-content" data-tab="basic">
                        <!-- Título do Orçamento -->
                        <div class="form-group">
                            <label for="titulo">Título do Orçamento</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" placeholder=" " required>
                        </div>

                        <!-- Categoria -->
                        <div class="form-group">
                            <label for="categoriaId">Categoria</label>
                            <select class="form-control" id="categoriaId" name="categoriaId" required>
                                <option value="" selected disabled></option>
                                <?php
                                $categorias = obterCategorias();
                                foreach ($categorias as $categoria) {
                                    echo "<option value='" . $categoria['ID_Categoria'] . "'>" . htmlspecialchars($categoria['Nome']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Valor Planejado -->
                        <div class="form-group value-container">
                            <label for="valorPlanejado">Valor Planejado</label>
                            <input type="number" class="form-control" id="valorPlanejado" name="valorPlanejado" step="0.01" placeholder=" " required>
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Período -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="form-group">
                                <label for="periodoInicio">Data Inicial</label>
                                <input type="date" class="form-control" id="periodoInicio" name="periodoInicio" placeholder=" " required>
                            </div>
                            
                            <div class="form-group">
                                <label for="periodoFim">Data Final</label>
                                <input type="date" class="form-control" id="periodoFim" name="periodoFim" placeholder=" " required>
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div class="form-group">
                            <label for="descricaoOrcamento">Descrição (opcional)</label>
                            <textarea class="form-control" id="descricaoOrcamento" name="descricaoOrcamento" placeholder=" " style="height: 100px"></textarea>
                        </div>

                        <!-- Status -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                            <label class="form-check-label" for="status">
                                Orçamento Ativo
                            </label>
                        </div>
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

<!-- Modal de Edição de Orçamento -->
<div class="modal fade" id="editarOrcamentoModal" tabindex="-1" aria-labelledby="editarOrcamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarOrcamentoModalLabel">Editar Orçamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="orcamento.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="editarOrcamento">
                <input type="hidden" id="editarOrcamentoId" name="orcamentoId">
                
                <div class="modal-body">
                    <div class="tab-content" data-tab="basic">
                        <!-- Título do Orçamento -->
                        <div class="form-group">
                            <label for="editarTitulo">Título do Orçamento</label>
                            <input type="text" class="form-control" id="editarTitulo" name="titulo" placeholder=" " required>
                        </div>

                        <!-- Categoria -->
                        <div class="form-group">
                            <label for="editarCategoriaId">Categoria</label>
                            <select class="form-control" id="editarCategoriaId" name="categoriaId" required>
                                <option value="" selected disabled></option>
                                <?php
                                foreach ($categorias as $categoria) {
                                    echo "<option value='" . $categoria['ID_Categoria'] . "'>" . htmlspecialchars($categoria['Nome']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Valor Planejado -->
                        <div class="form-group value-container">
                            <label for="editarValorPlanejado">Valor Planejado</label>
                            <input type="number" class="form-control" id="editarValorPlanejado" name="valorPlanejado" step="0.01" placeholder=" " required>
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Período -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="form-group">
                                <label for="editarPeriodoInicio">Data Inicial</label>
                                <input type="date" class="form-control" id="editarPeriodoInicio" name="periodoInicio" placeholder=" " required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editarPeriodoFim">Data Final</label>
                                <input type="date" class="form-control" id="editarPeriodoFim" name="periodoFim" placeholder=" " required>
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div class="form-group">
                            <label for="editarDescricaoOrcamento">Descrição (opcional)</label>
                            <textarea class="form-control" id="editarDescricaoOrcamento" name="descricaoOrcamento" placeholder=" " style="height: 100px"></textarea>
                        </div>

                        <!-- Status -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="editarStatus" name="status" value="1">
                            <label class="form-check-label" for="editarStatus">
                                Orçamento Ativo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Exclusão de Orçamento -->
<div class="modal fade" id="excluirOrcamentoModal" tabindex="-1" aria-labelledby="excluirOrcamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excluirOrcamentoModalLabel">Excluir Orçamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="orcamento.php" method="POST">
                <input type="hidden" name="acao" value="excluirOrcamento">
                <input type="hidden" id="excluirOrcamentoId" name="orcamentoId">
                
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Tem certeza que deseja excluir o orçamento:</p>
                    <p><strong id="excluirOrcamentoTitulo"></strong>?</p>
                    <p class="small text-danger">Esta ação não pode ser desfeita.</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>