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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializa todos os componentes dos modais
        initModalComponents();
        
        // Adiciona listeners para eventos dos modais
        setupModalListeners();
    });
    
    // Função principal para inicializar todos os componentes
    function initModalComponents() {
        setupTypeSelector();
        setupModalTabs();
        setupFormValidation();
    }
    
    // Configura listeners para eventos dos modais
    function setupModalListeners() {
        // Setup para abrir modal de edição
        const editButtons = document.querySelectorAll('[data-target="#editarCategoriaModal"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');
                const tipo = this.getAttribute('data-tipo');
                const descricao = this.getAttribute('data-descricao');
                const status = this.getAttribute('data-status') === '1';

                // Preenche os campos do formulário
                document.getElementById('editarCategoriaId').value = id;
                document.getElementById('editarNomeCategoria').value = nome;
                document.getElementById('editarTipoCategoria').value = tipo;
                document.getElementById('editarDescricaoCategoria').value = descricao;
                document.getElementById('editarStatusCategoria').checked = status;

                // Seleciona o tipo correto
                const editModal = document.getElementById('editarCategoriaModal');
                editModal.querySelectorAll('.type-option').forEach(opt => {
                    opt.classList.remove('active');
                    opt.classList.remove('income');
                    opt.classList.remove('expense');
                    
                    if (opt.getAttribute('data-type') === tipo) {
                        opt.classList.add('active');
                        opt.classList.add(tipo === 'Receita' ? 'income' : 'expense');
                    }
                });
            });
        });

        // Setup para abrir modal de exclusão
        const deleteButtons = document.querySelectorAll('[data-target="#excluirCategoriaModal"]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');

                document.getElementById('excluirCategoriaId').value = id;
                document.getElementById('excluirCategoriaNome').textContent = nome;
            });
        });
    }

    // Configura seletor de tipo
    function setupTypeSelector() {
        const typeOptions = document.querySelectorAll('.type-option');

        typeOptions.forEach(option => {
            option.addEventListener('click', function () {
                const type = this.getAttribute('data-type');
                const form = this.closest('form');

                // Atualiza estado visual
                form.querySelectorAll('.type-option').forEach(opt => {
                    opt.classList.remove('active');
                    opt.classList.remove('income');
                    opt.classList.remove('expense');
                });
                
                this.classList.add('active');
                if (type === 'Receita') {
                    this.classList.add('income');
                } else if (type === 'Despesa') {
                    this.classList.add('expense');
                }

                // Atualiza valor do input hidden
                if (form.querySelector('input[name="tipoCategoria"]')) {
                    form.querySelector('input[name="tipoCategoria"]').value = type;
                }
            });
        });
    }

    // Configura as abas do modal
    function setupModalTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');

        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                const tabType = this.getAttribute('data-tab');
                const modal = this.closest('.modal');

                // Atualiza botões de tab
                modal.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Atualiza visibilidade do conteúdo
                modal.querySelectorAll('.tab-content').forEach(content => {
                    content.style.display = 'none';
                });
                modal.querySelector(`.tab-content[data-tab="${tabType}"]`).style.display = 'block';
            });
        });
    }

    // Configura validação de formulário
    function setupFormValidation() {
        const forms = document.querySelectorAll('form.needs-validation');

        forms.forEach(form => {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Feedback visual
                    const invalidFields = form.querySelectorAll(':invalid');
                    if (invalidFields.length > 0) {
                        invalidFields[0].focus();

                        // Mostra a tab que contém o campo inválido
                        const tabContent = invalidFields[0].closest('.tab-content');
                        if (tabContent) {
                            const tabId = tabContent.getAttribute('data-tab');
                            const tabBtn = form.querySelector(`.tab-btn[data-tab="${tabId}"]`);
                            if (tabBtn) {
                                tabBtn.click();
                            }
                        }
                    }
                }

                form.classList.add('was-validated');
            });
        });
    }

</script>

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

