document.addEventListener('DOMContentLoaded', function() {
    //
    // === UTILS DE URL ===
    //
    const PARAM_KEY = 'periodo';

    function getPeriodoFromURL() {
        const params = new URLSearchParams(window.location.search);
        return params.get(PARAM_KEY) || 'current-month';
    }

    function setPeriodoInURL(periodo) {
        const params = new URLSearchParams(window.location.search);
        params.set(PARAM_KEY, periodo);
        window.history.pushState({}, '', `${window.location.pathname}?${params}`);
    }

    //
    // === SELETORES ===
    //
    const togglePeriodFilter = document.getElementById('togglePeriodFilter');
    const periodFilterContent = document.getElementById('periodFilterContent');
    const statusOptions = document.querySelectorAll('.status-option');
    const periodSelection = document.getElementById('periodSelection');
    const customPeriodSection = document.getElementById('customPeriodSection');
    const clearPeriodFilter = document.getElementById('clearPeriodFilter');
    const applyPeriodFilter = document.getElementById('applyPeriodFilter');

    //
    // === INICIALIZAÇÃO A PARTIR DA URL ===
    //
    function initPeriodoFromURL() {
        const periodoParam = getPeriodoFromURL();
        let matched = false;

        statusOptions.forEach(opt => {
            const p = opt.getAttribute('data-period');
            if (p === periodoParam && p !== 'custom') {
                opt.classList.add('active');
                periodSelection.value = p;
                customPeriodSection.style.display = 'none';
                matched = true;
            }
        });

        if (!matched && periodoParam.startsWith('custom')) {
            // formato custom: YYYY-MM-DD_YYYY-MM-DD
            const [, range] = periodoParam.split('=');
            const [start, end] = periodoParam.split('_');
            document.querySelector('.status-option[data-period="custom"]').classList.add('active');
            periodSelection.value = 'custom';
            customPeriodSection.style.display = 'block';
            document.getElementById('startDate').value = start;
            document.getElementById('endDate').value = end;
        }

        // se não achar nada, força default
        if (!matched && periodSelection.value === '') {
            document.querySelector('.status-option[data-period="current-month"]').classList.add('active');
            periodSelection.value = 'current-month';
            customPeriodSection.style.display = 'none';
        }
    }

    initPeriodoFromURL();

    //
    // === TOGGLE DO FILTRO ===
    //
    togglePeriodFilter.addEventListener('click', function() {
        const isVisible = periodFilterContent.style.display !== 'none';
        const chevron = this.querySelector('i');
        chevron.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';

        if (isVisible) {
            periodFilterContent.classList.add('fade-out');
            setTimeout(() => {
                periodFilterContent.style.display = 'none';
                periodFilterContent.classList.remove('fade-out');
            }, 300);
        } else {
            periodFilterContent.style.display = 'block';
            periodFilterContent.classList.add('fade-in');
            setTimeout(() => periodFilterContent.classList.remove('fade-in'), 300);
        }
    });

    //
    // === SELEÇÃO DE PERÍODO (PILLS) ===
    //
    statusOptions.forEach(option => {
        option.addEventListener('click', function() {
            statusOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');

            const periodType = this.getAttribute('data-period');
            periodSelection.value = periodType;
            customPeriodSection.style.display = periodType === 'custom' ? 'block' : 'none';

            if (periodType === 'custom') {
                customPeriodSection.classList.add('fade-in-up');
                setTimeout(() => customPeriodSection.classList.remove('fade-in-up'), 500);
            }
        });
    });

    //
    // === LIMPAR FILTROS ===
    //
    clearPeriodFilter.addEventListener('click', function() {
        statusOptions.forEach(opt => {
            opt.classList.toggle('active', opt.getAttribute('data-period') === 'current-month');
        });
        periodSelection.value = 'current-month';
        customPeriodSection.style.display = 'none';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';

        this.classList.add('pulse');
        setTimeout(() => this.classList.remove('pulse'), 500);

        setPeriodoInURL('current-month');
    });

    //
    // === APLICAR FILTROS ===
    //
    applyPeriodFilter.addEventListener('click', function() {
        // Determina o valor para a URL
        const periodType = periodSelection.value;
        let periodoURL = periodType;

        if (periodType === 'custom') {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            periodoURL = `${startDate}_${endDate}`;
        }

        setPeriodoInURL(periodoURL);

        // Notificação visual (mantive seu código)
        const notification = document.createElement('div');
        // ... resto da criação da notificação ...
        // (manter seu HTML/CSS como estava)

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 4000);

        // Loading feedback
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Aplicando...';
        this.disabled = true;

        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
            document.querySelectorAll('.summary-card').forEach(card => {
                card.classList.add('pulse');
                setTimeout(() => card.classList.remove('pulse'), 500);
            });
            togglePeriodFilter.click();
        }, 1200);

        // Aqui você chamaria suas APIs / atualizaria gráficos...
    });

    //
    // === DATAS DEFAULT PARA CUSTOM ===
    //
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    const formatDate = date => {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    };

    document.getElementById('startDate').value = formatDate(firstDayOfMonth);
    document.getElementById('endDate').value = formatDate(today);
});
