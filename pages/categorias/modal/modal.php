<?php
// Arquivo: pages/categorias/modal/modal.php
require 'categorias/script.php';
?>

<!-- Link para FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    #despesa:hover {
        border-color: red;
        background-color: rgb(223, 67, 67);
    }

    #receita:hover {
        border-color: var(--color-primary-500);
        background-color: var(--color-primary-500);
    }
</style>
<!-- Nova Categoria Modal -->
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoriaModalLabel">Nova Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <div class="tab-content" data-tab="basic">
                        <!-- Tipo de Categoria -->
                        <h6 class="mb-3">Tipo de Categoria</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option active" id="despesa" data-type="Despesa">
                                <i class="fas fa-arrow-down type-icon" id="despesa" id="setaDes"></i>
                                <span class="type-name">Despesa</span>
                            </div>
                            <div class="type-option receita" id="receita" data-type="Receita">
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
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Descrição -->
                        <label for="descricaoCategoria">Descrição (opcional)</label>
                        <div class="form-group">
                            <textarea class="form-control" id="descricaoCategoria" name="descricaoCategoria"
                                placeholder=" " rows="3"></textarea>
                        </div>

                        <!-- Status -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="statusCategoria" name="statusCategoria"
                                value="true" checked>
                            <label class="form-check-label" for="statusCategoria">
                                Categoria Ativa
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

<!-- Editar Categoria Modal -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-labelledby="editarCategoriaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <div class="tab-content" data-tab="basic">
                        <!-- Tipo de Categoria -->
                        <h6 class="mb-3">Tipo de Categoria</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option" data-type="Despesa">
                                <i class="fas fa-arrow-down type-icon"></i>
                                <span class="type-name">Despesa</span>
                            </div>
                            <div class="type-option" data-type="Receita">
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
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Descrição -->
                        <div class="form-group">
                            <textarea class="form-control" id="editarDescricaoCategoria" name="descricaoCategoria"
                                placeholder=" " rows="3"></textarea>
                            <label for="editarDescricaoCategoria">Descrição (opcional)</label>
                        </div>

                        <!-- Status -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="editarStatusCategoria"
                                name="statusCategoria" value="true">
                            <label class="form-check-label" for="editarStatusCategoria">
                                Categoria Ativa
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

<!-- Excluir Categoria Modal -->
<div class="modal fade" id="excluirCategoriaModal" tabindex="-1" aria-labelledby="excluirCategoriaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excluirCategoriaModalLabel">Excluir Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para funcionalidades dos modais -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Set up type selector
        const setupTypeSelector = () => {
            const typeOptions = document.querySelectorAll('.type-option');

            typeOptions.forEach(option => {
                option.addEventListener('click', function () {
                    const type = this.getAttribute('data-type');
                    const form = this.closest('form');

                    // Update visual state
                    form.querySelectorAll('.type-option').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');

                    // Update hidden input value
                    if (form.querySelector('input[name="tipoCategoria"]')) {
                        form.querySelector('input[name="tipoCategoria"]').value = type;
                    }
                });
            });
        };

        // Handle edit modal
        const setupEditModal = () => {
            const editButtons = document.querySelectorAll('[data-target="#editarCategoriaModal"]');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nome = this.getAttribute('data-nome');
                    const tipo = this.getAttribute('data-tipo');
                    const descricao = this.getAttribute('data-descricao');
                    const status = this.getAttribute('data-status') === '1';

                    // Fill form fields
                    document.getElementById('editarCategoriaId').value = id;
                    document.getElementById('editarNomeCategoria').value = nome;
                    document.getElementById('editarTipoCategoria').value = tipo;
                    document.getElementById('editarDescricaoCategoria').value = descricao;
                    document.getElementById('editarStatusCategoria').checked = status;

                    // Set active type option
                    document.querySelectorAll('#editarCategoriaModal .type-option').forEach(opt => {
                        opt.classList.remove('active');
                        if (opt.getAttribute('data-type') === tipo) {
                            opt.classList.add('active');
                        }
                    });
                });
            });
        };

        // Handle delete modal
        const setupDeleteModal = () => {
            const deleteButtons = document.querySelectorAll('[data-target="#excluirCategoriaModal"]');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nome = this.getAttribute('data-nome');

                    document.getElementById('excluirCategoriaId').value = id;
                    document.getElementById('excluirCategoriaNome').textContent = nome;
                });
            });
        };

        // Initialize modal tabs
        const setupModalTabs = () => {
            const tabButtons = document.querySelectorAll('.tab-btn');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const tabType = this.getAttribute('data-tab');
                    const modal = this.closest('.modal');

                    // Update tab buttons
                    modal.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Update content visibility
                    modal.querySelectorAll('.tab-content').forEach(content => {
                        content.style.display = 'none';
                    });
                    modal.querySelector(`.tab-content[data-tab="${tabType}"]`).style.display = 'block';
                });
            });
        };

        // Form validation
        const setupFormValidation = () => {
            const forms = document.querySelectorAll('form.needs-validation');

            forms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();

                        // Visual feedback
                        const invalidFields = form.querySelectorAll(':invalid');
                        if (invalidFields.length > 0) {
                            invalidFields[0].focus();

                            // Show the tab containing the invalid field
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
        };

        // Initialize floating labels
        const setupFloatingLabels = () => {
            const formControls = document.querySelectorAll('.form-control');

            formControls.forEach(input => {
                // Initial state
                if (input.value) {
                    input.classList.add('has-value');
                }

                // Add listeners
                input.addEventListener('focus', function () {
                    this.classList.add('has-value');
                });

                input.addEventListener('blur', function () {
                    if (!this.value) {
                        this.classList.remove('has-value');
                    }
                });
            });
        };

        // Run all setup functions
        setupTypeSelector();
        setupEditModal();
        setupDeleteModal();
        setupModalTabs();
        setupFormValidation();
        setupFloatingLabels();
    });
</script>