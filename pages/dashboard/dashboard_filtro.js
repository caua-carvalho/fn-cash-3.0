document.addEventListener('DOMContentLoaded', () => {
  const PARAM_PERIODO = 'periodo';
  const PARAM_INI    = 'dataInicio';
  const PARAM_FIM    = 'dataFim';

  const btnsPeriodo   = document.querySelectorAll('.status-option');
  const inputHidden   = document.getElementById('periodSelection');
  const sectionCustom = document.getElementById('customPeriodSection');
  const inputIni      = document.getElementById('startDate');
  const inputFim      = document.getElementById('endDate');
  const btnAplicar    = document.getElementById('applyPeriodFilter');
  const btnLimpar     = document.getElementById('clearPeriodFilter');
  const btnToggle     = document.getElementById('togglePeriodFilter');
  const contentFilter = document.getElementById('periodFilterContent');

  // Pegar query atual
  const qs = new URLSearchParams(window.location.search);

  // === Inicialização ===
  function init() {
    const per = qs.get(PARAM_PERIODO) || 'mes-atual';
    // Marca botão ativo
    btnsPeriodo.forEach(b => {
      if (b.dataset.period === per) b.classList.add('active');
    });
    inputHidden.value = per;
    // Se for customizado, mostra e preenche
    if (per === 'customizado') {
      sectionCustom.style.display = 'block';
      const i = qs.get(PARAM_INI);
      const f = qs.get(PARAM_FIM);
      if (i) inputIni.value = i;
      if (f) inputFim.value = f;
    }
  }

  // === Construir e navegar pra URL limpa ===
  function navegar(periodo, inicio, fim) {
    const base = window.location.origin + window.location.pathname;
    const params = new URLSearchParams();
    params.set(PARAM_PERIODO, periodo);
    if (periodo === 'customizado') {
      params.set(PARAM_INI, inicio);
      params.set(PARAM_FIM, fim);
    }
    window.location.href = `${base}?${params.toString()}`;
  }

  // === Handlers ===
  btnsPeriodo.forEach(btn => {
    btn.addEventListener('click', e => {
      btnsPeriodo.forEach(b => b.classList.remove('active'));
      e.currentTarget.classList.add('active');
      const per = e.currentTarget.dataset.period;
      inputHidden.value = per;
      sectionCustom.style.display = per === 'customizado' ? 'block' : 'none';
    });
  });

  btnAplicar.addEventListener('click', () => {
    const per = inputHidden.value;
    if (per === 'customizado') {
      const i = inputIni.value;
      const f = inputFim.value;
      if (!i || !f) {
        alert('Preencha as datas inicial e final!');
        return;
      }
      navegar('customizado', i, f);
    } else {
      navegar(per);
    }
  });

  btnLimpar.addEventListener('click', () => {
    navegar('mes-atual');
  });

  btnToggle.addEventListener('click', () => {
    const vis = contentFilter.style.display === 'block';
    contentFilter.style.display = vis ? 'none' : 'block';
    btnToggle.querySelector('i').classList.toggle('fa-chevron-down');
    btnToggle.querySelector('i').classList.toggle('fa-chevron-up');
  });

  // Liga tudo
  init();
});
