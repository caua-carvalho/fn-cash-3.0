/**
 * Abre um modal pelo seletor universal.
 * @param {string} modalId - Seletor do modal (ex: "#meuModal")
 */
function abrirModal(modalId) {
    const modal = document.querySelector(modalId);
    if (!modal) return;
    modal.classList.add('show');
    modal.style.display = 'block';
    document.body.classList.add('modal-open');
    criarBackdrop(modalId);
}

/**
 * Fecha um modal pelo seletor universal.
 * @param {string} modalId - Seletor do modal (ex: "#meuModal")
 */
function fecharModal(modalId) {
    const modal = document.querySelector(modalId);
    if (!modal) return;
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    removerBackdrop();
}

/**
 * Cria o backdrop do modal se não existir.
 * @param {string} modalId - Seletor do modal
 */
function criarBackdrop(modalId) {
    if (document.querySelector('.modal-backdrop')) return;
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop show';
    backdrop.addEventListener('click', () => fecharModal(modalId));
    document.body.appendChild(backdrop);
}

/**
 * Remove o backdrop do modal.
 */
function removerBackdrop() {
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) backdrop.remove();
}

/**
 * Inicializa listeners universais para todos os modais do sistema.
 * Chama isso uma vez no DOMContentLoaded.
 */
function inicializarModaisUniversais() {
    // Botões que abrem modais
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const modalId = btn.getAttribute('data-modal-open');
            abrirModal(modalId);
        });
    });

    // Botões que fecham modais
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', function () {
            const modal = btn.closest('.modal');
            if (modal) fecharModal('#' + modal.id);
        });
    });

    // Fecha modal no ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modal = document.querySelector('.modal.show');
            if (modal) fecharModal('#' + modal.id);
        }
    });
}

// Inicializa tudo ao carregar a página
document.addEventListener('DOMContentLoaded', function () {
    inicializarModaisUniversais();

    // Exemplo: listeners específicos para modais de contas
    const editButtons = document.querySelectorAll('[data-modal-open="#editarContaModal"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            const tipo = this.getAttribute('data-tipo');
            const saldo = this.getAttribute('data-saldo');
            const instituicao = this.getAttribute('data-instituicao');
            document.getElementById('editarContaId').value = id;
            document.getElementById('editarNomeConta').value = nome;
            document.getElementById('editarTipoConta').value = tipo;
            document.getElementById('editarSaldoConta').value = saldo;
            document.getElementById('editarInstituicaoConta').value = instituicao;
        });
    });

    const deleteButtons = document.querySelectorAll('[data-modal-open="#excluirContaModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            document.getElementById('excluirContaId').value = id;
        });
    });
});