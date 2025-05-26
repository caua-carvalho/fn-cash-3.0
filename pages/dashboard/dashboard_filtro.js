// Gerado pelo Copilot

document.addEventListener('DOMContentLoaded', function() {
    // Constantes para evitar magic numbers
    const PARAM_KEY = 'periodo';
    const ANIMACAO_CURTA = 300;
    const ANIMACAO_MEDIA = 500;
    const ANIMACAO_LONGA = 1200;

    // Seletores de elementos do DOM
    const botaoToggleFiltroPeriodo = document.getElementById('togglePeriodFilter');
    const conteudoFiltroPeriodo = document.getElementById('periodFilterContent');
    const botoesPeriodo = document.querySelectorAll('.status-option');
    const inputPeriodoSelecionado = document.getElementById('periodSelection');
    const secaoPeriodoCustomizado = document.getElementById('customPeriodSection');
    const botaoLimparFiltro = document.getElementById('clearPeriodFilter');
    const botaoAplicarFiltro = document.getElementById('applyPeriodFilter');
    const inputDataInicio = document.getElementById('startDate');
    const inputDataFim = document.getElementById('endDate');

    /**
     * Retorna o período atual da URL ou 'mes-atual' se não houver.
     */
    function obterPeriodoDaURL() {
        const params = new URLSearchParams(window.location.search);
        return params.get(PARAM_KEY) || 'mes-atual';
    }

    /**
     * Atualiza o parâmetro de período na URL, incluindo dataInicio/dataFim se customizado.
     * Gerado pelo Copilot
     */
    function atualizarPeriodoNaURL(periodo) {
        const params = new URLSearchParams(window.location.search);

        if (periodo === 'customizado') {
            const inicio = inputDataInicio.value;
            const fim = inputDataFim.value;
            params.set('periodo', 'customizado');
            params.set('dataInicio', inicio);
            params.set('dataFim', fim);
        } else if (periodo.includes('_')) {
            const [inicio, fim] = periodo.split('_');
            params.set('periodo', 'customizado');
            params.set('dataInicio', inicio);
            params.set('dataFim', fim);
        } else {
            params.set('periodo', periodo);
            params.delete('dataInicio');
            params.delete('dataFim');
        }

        window.location.search = params.toString();
    }

    /**
     * Inicializa o filtro de período com base na URL.
     */
    function inicializarFiltroPeriodo() {
        const periodoParam = obterPeriodoDaURL();
        let encontrou = false;

        botoesPeriodo.forEach(botao => {
            const periodoBotao = botao.getAttribute('data-period');
            if (periodoBotao === periodoParam && periodoBotao !== 'customizado') {
                botao.classList.add('active');
                inputPeriodoSelecionado.value = periodoBotao;
                secaoPeriodoCustomizado.style.display = 'none';
                encontrou = true;
            }
        });

        if (!encontrou && periodoParam.includes('_')) {
            // formato customizado: YYYY-MM-DD_YYYY-MM-DD
            const [inicio, fim] = periodoParam.split('_');
            const botaoCustomizado = document.querySelector('.status-option[data-period="customizado"]');
            if (botaoCustomizado) botaoCustomizado.classList.add('active');
            inputPeriodoSelecionado.value = 'customizado';
            secaoPeriodoCustomizado.style.display = 'block';
            inputDataInicio.value = inicio;
            inputDataFim.value = fim;
        }

        if (!encontrou && inputPeriodoSelecionado.value === '') {
            const botaoMesAtual = document.querySelector('.status-option[data-period="mes-atual"]');
            if (botaoMesAtual) botaoMesAtual.classList.add('active');
            inputPeriodoSelecionado.value = 'mes-atual';
            secaoPeriodoCustomizado.style.display = 'none';
        }
    }

    /**
     * Alterna a exibição do filtro de período.
     */
    function alternarFiltroPeriodo() {
        const estaVisivel = conteudoFiltroPeriodo.style.display !== 'none';
        const iconeChevron = botaoToggleFiltroPeriodo.querySelector('i');
        iconeChevron.style.transform = estaVisivel ? 'rotate(0deg)' : 'rotate(180deg)';

        if (estaVisivel) {
            conteudoFiltroPeriodo.classList.add('fade-out');
            setTimeout(() => {
                conteudoFiltroPeriodo.style.display = 'none';
                conteudoFiltroPeriodo.classList.remove('fade-out');
            }, ANIMACAO_CURTA);
            return;
        }
        conteudoFiltroPeriodo.style.display = 'block';
        conteudoFiltroPeriodo.classList.add('fade-in');
        setTimeout(() => conteudoFiltroPeriodo.classList.remove('fade-in'), ANIMACAO_CURTA);
    }

    /**
     * Marca o botão de período selecionado e exibe/esconde o customizado.
     */
    function selecionarPeriodo(evento) {
        botoesPeriodo.forEach(botao => botao.classList.remove('active'));
        const botaoClicado = evento.currentTarget;
        botaoClicado.classList.add('active');

        const periodoSelecionado = botaoClicado.getAttribute('data-period');
        inputPeriodoSelecionado.value = periodoSelecionado;
        secaoPeriodoCustomizado.style.display = periodoSelecionado === 'customizado' ? 'block' : 'none';

        if (periodoSelecionado === 'customizado') {
            secaoPeriodoCustomizado.classList.add('fade-in-up');
            setTimeout(() => secaoPeriodoCustomizado.classList.remove('fade-in-up'), ANIMACAO_MEDIA);
        }
    }

    /**
     * Limpa o filtro de período para o padrão.
     */
    function limparFiltroPeriodo() {
        botoesPeriodo.forEach(botao => {
            botao.classList.toggle('active', botao.getAttribute('data-period') === 'mes-atual');
        });
        inputPeriodoSelecionado.value = 'mes-atual';
        secaoPeriodoCustomizado.style.display = 'none';
        inputDataInicio.value = '';
        inputDataFim.value = '';

        botaoLimparFiltro.classList.add('pulse');
        setTimeout(() => botaoLimparFiltro.classList.remove('pulse'), ANIMACAO_MEDIA);

        atualizarPeriodoNaURL('mes-atual');
    }

    /**
     * Aplica o filtro de período selecionado.
     * Gerado pelo Copilot
     */
    function aplicarFiltroPeriodo() {
        const periodoSelecionado = inputPeriodoSelecionado.value;
        let periodoURL = periodoSelecionado;

        if (periodoSelecionado === 'customizado') {
            const inicio = inputDataInicio.value;
            const fim = inputDataFim.value;
            if (!inicio || !fim) {
                alert('Preencha as datas inicial e final!');
                return;
            }
            // Aqui, passa só 'customizado', as datas vão como params separados
            periodoURL = 'customizado';
        }

        atualizarPeriodoNaURL(periodoURL);

        // O resto não faz sentido pois vai recarregar a página
    }

    /**
     * Mostra uma notificação visual simples.
     */
    function mostrarNotificacao(mensagem) {
        const notificacao = document.createElement('div');
        notificacao.className = 'notification fade-in';
        notificacao.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${mensagem}`;
        document.body.appendChild(notificacao);
        setTimeout(() => {
            notificacao.classList.add('fade-out');
            setTimeout(() => notificacao.remove(), ANIMACAO_CURTA);
        }, 4000);
    }

    /**
     * Mostra loading no botão e executa callback depois.
     */
    function mostrarLoadingBotao(botao, callback) {
        const textoOriginal = botao.innerHTML;
        botao.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Aplicando...';
        botao.disabled = true;
        setTimeout(() => {
            botao.innerHTML = textoOriginal;
            botao.disabled = false;
            if (typeof callback === 'function') callback();
        }, ANIMACAO_LONGA);
    }

    /**
     * Anima os cards de resumo.
     */
    function animarCardsResumo() {
        document.querySelectorAll('.summary-card').forEach(card => {
            card.classList.add('pulse');
            setTimeout(() => card.classList.remove('pulse'), ANIMACAO_MEDIA);
        });
    }

    /**
     * Define datas padrão para o período customizado.
     */
    function definirDatasPadraoCustomizado() {
        const hoje = new Date();
        const primeiroDiaMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
        inputDataInicio.value = formatarData(primeiroDiaMes);
        inputDataFim.value = formatarData(hoje);
    }

    /**
     * Formata data para yyyy-mm-dd.
     */
    function formatarData(data) {
        const y = data.getFullYear();
        const m = String(data.getMonth() + 1).padStart(2, '0');
        const d = String(data.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // --- Inicialização ---
    inicializarFiltroPeriodo();
    definirDatasPadraoCustomizado();

    // --- Eventos ---
    botaoToggleFiltroPeriodo.addEventListener('click', alternarFiltroPeriodo);
    botoesPeriodo.forEach(botao => botao.addEventListener('click', selecionarPeriodo));
    botaoLimparFiltro.addEventListener('click', limparFiltroPeriodo);
    botaoAplicarFiltro.addEventListener('click', aplicarFiltroPeriodo);

    // Atualização dos gráficos/listagens quando o período mudar
    window.addEventListener('periodoAtualizado', () => {
        // Aqui você pode disparar AJAX ou atualizar gráficos sem reload
        // Exemplo: atualizarGraficos();
    });
});
