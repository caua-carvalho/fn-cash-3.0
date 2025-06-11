<!-- Arquivo: pages/categorias/modal/modal.php -->
<?php
// Arquivo: pages/categorias/modal/modal.php
require_once 'categorias/script.php';
?>

<!-- Modal de Nova Categoria -->
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoriaModalLabel">Nova Categoria</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-modal-close='#categoriaModal' aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="categorias.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="cadastrarCategoria">

                <div class="modal-body">
                    <div class="tab-content active" data-tab="basic">
                        <!-- Tipo de Categoria -->
                        <h6 class="mb-3">Tipo de Categoria</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option expense active" data-type="Despesa">
                                <i class="fas fa-arrow-down type-icon"></i>
                                <span class="type-name">Despesa</span>
                            </div>
                            <div class="type-option income" data-type="Receita">
                                <i class="fas fa-arrow-up type-icon"></i>
                                <span class="type-name">Receita</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipoCategoria" id="tipoCategoria" value="Despesa">

                        <!-- Nome da Categoria -->
                        <label for="nomeCategoria">Nome da Categoria</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="nomeCategoria" name="nomeCategoria" 
                                placeholder=" " required>
                            <div class="invalid-feedback">
                                Por favor, informe um nome para a categoria.
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="statusCategoria" name="statusCategoria"
                                value="true" checked>
                            <label class="form-check-label" for="statusCategoria">
                                Categoria Ativa
                            </label>
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Apenas a Descrição -->
                        <label for="descricaoCategoria">Descrição (opcional)</label>
                        <div class="form-group">
                            <textarea class="form-control" id="descricaoCategoria" name="descricaoCategoria" 
                                placeholder=" " rows="5"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-modal-close="#categoriaModal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Editar Categoria -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-labelledby="editarCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoria</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-modal-close="#editarCategoriaModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="categorias.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="editarCategoria">
                <input type="hidden" id="editarCategoriaId" name="categoriaId">

                <div class="modal-body">
                    <div class="tab-content active" data-tab="basic">
                        <!-- Tipo de Categoria -->
                        <h6 class="mb-3">Tipo de Categoria</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option expense" data-type="Despesa">
                                <i class="fas fa-arrow-down type-icon"></i>
                                <span class="type-name">Despesa</span>
                            </div>
                            <div class="type-option income" data-type="Receita">
                                <i class="fas fa-arrow-up type-icon"></i>
                                <span class="type-name">Receita</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipoCategoria" id="editarTipoCategoria" value="Despesa">

                        <!-- Nome da Categoria -->
                        <label for="editarNomeCategoria">Nome da Categoria</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="editarNomeCategoria" name="nomeCategoria" 
                                placeholder=" " required>
                            <div class="invalid-feedback">
                                Por favor, informe um nome para a categoria.
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="editarStatusCategoria" 
                                name="statusCategoria" value="true">
                            <label class="form-check-label" for="editarStatusCategoria">
                                Categoria Ativa
                            </label>
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Apenas a Descrição -->
                        <label for="editarDescricaoCategoria">Descrição (opcional)</label>
                        <div class="form-group">
                            <textarea class="form-control" id="editarDescricaoCategoria" name="descricaoCategoria" 
                                placeholder=" " rows="5"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-modal-close="#editarCategoriaModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Excluir Categoria Modal -->
<div class="modal fade" id="excluirCategoriaModal" tabindex="-1" aria-labelledby="excluirCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excluirCategoriaModalLabel">Excluir Categoria</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-modal-close="#excluirCategoriaModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="categorias.php" method="POST" id="excluirCategoriaForm">
                <input type="hidden" name="acao" value="excluirCategoria">
                <input type="hidden" id="excluirCategoriaId" name="categoriaId">

                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Tem certeza que deseja excluir a categoria:</p>
                    <p><strong id="excluirCategoriaNome"></strong>?</p>
                    <p class="small text-danger">Esta ação não pode ser desfeita.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-modal-close="#excluirCategoriaModal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para funcionalidades dos modais -->
<script src="categorias/modal/modal.js"></script>

<style>
    /* Estilos para type-option consistent com Design System */
    .type-option.active.expense {
        background-color: rgba(231, 76, 60, 0.1);
        border-color: var(--color-expense);
    }

    .type-option.active.income {
        background-color: rgba(7, 163, 98, 0.1);
        border-color: var(--color-income);
    }

    .dark-theme .type-option.active.expense {
        background-color: rgba(231, 76, 60, 0.2);
    }

    .dark-theme .type-option.active.income {
        background-color: rgba(7, 163, 98, 0.2);
    }
</style>

<link rel="stylesheet" href="contas/modal/modal.css">

