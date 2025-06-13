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

    // ------ Filtros de categorias ------
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroStatus = document.getElementById('filtroStatus');
    const filtroBusca = document.getElementById('filtroBusca');
    const btnLimpar = document.getElementById('limparFiltros');
    const btnAplicar = document.getElementById('aplicarFiltros');

    function filtrarCategorias() {
        const tipo = filtroTipo ? filtroTipo.value : 'todos';
        const status = filtroStatus ? filtroStatus.value : 'todos';
        const busca = filtroBusca ? filtroBusca.value.toLowerCase().trim() : '';

        const linhas = document.querySelectorAll('#tabelaCategorias tr.categoria');
        let encontrado = false;

        linhas.forEach(linha => {
            const nome = linha.dataset.nome.toLowerCase();
            const tipoCategoria = linha.dataset.tipo.toLowerCase();
            const statusCategoria = linha.dataset.status.toLowerCase();
            const descricao = (linha.dataset.descricao || '').toLowerCase();

            const passaTipo = tipo === 'todos' || tipoCategoria === tipo.toLowerCase();
            let passaStatus = true;
            if (status === 'ativas') {
                passaStatus = statusCategoria === 'ativa';
            } else if (status === 'inativas') {
                passaStatus = statusCategoria === 'inativa';
            }

            const passaBusca = busca === '' || nome.includes(busca) || descricao.includes(busca);

            if (passaTipo && passaStatus && passaBusca) {
                linha.style.display = '';
                encontrado = true;
            } else {
                linha.style.display = 'none';
            }
        });

        const tbody = document.getElementById('tabelaCategorias');
        const msg = document.getElementById('msgNaoEncontrado');
        if (!encontrado) {
            if (!msg) {
                const nova = document.createElement('tr');
                nova.id = 'msgNaoEncontrado';
                nova.innerHTML = `
                    <td colspan="5" class="text-center py-4">
                        <i class="fas fa-search fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Nenhuma categoria encontrada com os filtros selecionados.</p>
                    </td>
                `;
                tbody.appendChild(nova);
            }
        } else if (msg) {
            msg.remove();
        }
    }

    if (btnAplicar) btnAplicar.addEventListener('click', filtrarCategorias);
    if (btnLimpar) btnLimpar.addEventListener('click', () => {
        if (filtroTipo) filtroTipo.value = 'todos';
        if (filtroStatus) filtroStatus.value = 'todos';
        if (filtroBusca) filtroBusca.value = '';
        filtrarCategorias();
    });
    if (filtroTipo) filtroTipo.addEventListener('change', filtrarCategorias);
    if (filtroStatus) filtroStatus.addEventListener('change', filtrarCategorias);
    if (filtroBusca) filtroBusca.addEventListener('input', filtrarCategorias);
});