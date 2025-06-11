// JavaScript utilidades para os modais de categorias

document.addEventListener('DOMContentLoaded', function () {
    initCategoriaModals();
    VALIDAR_MODAL();
});

function initCategoriaModals() {
    setupTypeSelector();
    setupModalTabs();
    setupFormValidation();
    setupModalListeners();
}

function setupModalListeners() {
    const editButtons = document.querySelectorAll('[data-modal-open="#editarCategoriaModal"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            const tipo = this.getAttribute('data-tipo');
            const descricao = this.getAttribute('data-descricao');
            const status = this.getAttribute('data-status') === '1';

            document.getElementById('editarCategoriaId').value = id;
            document.getElementById('editarNomeCategoria').value = nome;
            document.getElementById('editarTipoCategoria').value = tipo;
            document.getElementById('editarDescricaoCategoria').value = descricao;
            document.getElementById('editarStatusCategoria').checked = status;

            const editModal = document.getElementById('editarCategoriaModal');
            editModal.querySelectorAll('.type-option').forEach(opt => {
                opt.classList.remove('active', 'income', 'expense');
                if (opt.getAttribute('data-type') === tipo) {
                    opt.classList.add('active');
                    opt.classList.add(tipo === 'Receita' ? 'income' : 'expense');
                }
            });
        });
    });

    const deleteButtons = document.querySelectorAll('[data-modal-open="#excluirCategoriaModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            document.getElementById('excluirCategoriaId').value = id;
            document.getElementById('excluirCategoriaNome').textContent = nome;
        });
    });
}

function setupTypeSelector() {
    const typeOptions = document.querySelectorAll('.type-option');
    typeOptions.forEach(option => {
        option.addEventListener('click', function () {
            const type = this.getAttribute('data-type');
            const form = this.closest('form');

            form.querySelectorAll('.type-option').forEach(opt => {
                opt.classList.remove('active', 'income', 'expense');
            });

            this.classList.add('active');
            if (type === 'Receita') {
                this.classList.add('income');
            } else if (type === 'Despesa') {
                this.classList.add('expense');
            }

            const hidden = form.querySelector('input[name="tipoCategoria"]');
            if (hidden) hidden.value = type;
        });
    });
}

function setupModalTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const tabType = this.getAttribute('data-tab');
            const modal = this.closest('.modal');

            modal.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            modal.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            modal.querySelector(`.tab-content[data-tab="${tabType}"]`).style.display = 'block';
        });
    });
}

function setupFormValidation() {
    const forms = document.querySelectorAll('form.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                const invalidFields = form.querySelectorAll(':invalid');
                if (invalidFields.length > 0) {
                    invalidFields[0].focus();
                    const tabContent = invalidFields[0].closest('.tab-content');
                    if (tabContent) {
                        const tabId = tabContent.getAttribute('data-tab');
                        const tabBtn = form.querySelector(`.tab-btn[data-tab="${tabId}"]`);
                        if (tabBtn) tabBtn.click();
                    }
                }
            }
            form.classList.add('was-validated');
        });
    });
}

function verificarSeModalAberto(id) {
    const modal = document.getElementById(id);
    return modal && modal.classList.contains('show');
}

function VALIDAR_MODAL() {
    verificarSeModalAberto('categoriaModal');
    verificarSeModalAberto('editarCategoriaModal');
    verificarSeModalAberto('excluirCategoriaModal');
}
