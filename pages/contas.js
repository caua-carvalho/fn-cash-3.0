document.addEventListener('DOMContentLoaded', () => {
  const grid  = document.getElementById('contasGrid');
  const cards = Array.from(document.querySelectorAll('.account-card'));

  // 1) Agrupa os cards em colunas por tipo de conta
  const colCorrente = document.createElement('div');
  const colPoupanca = document.createElement('div');
  const colOutros   = document.createElement('div');
  [colCorrente, colPoupanca, colOutros].forEach(c => c.className = 'contas-col');

  grid.innerHTML = '';
  grid.append(colCorrente, colPoupanca, colOutros);

  cards.forEach(card => {
    const tipo = card.getAttribute('data-tipo');
    if (tipo === 'Corrente') {
      colCorrente.appendChild(card);
    } else if (tipo === 'Poupança') {
      colPoupanca.appendChild(card);
    } else {
      colOutros.appendChild(card);
    }
  });

  // 2) Abre modais sem propagar o clique ao card
  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      const modal = new bootstrap.Modal(
        document.querySelector(btn.getAttribute('data-modal-open'))
      );
      modal.show();
    });
  });

  // 3) Filtragem de cards
  const searchInput  = document.getElementById('searchConta');
  const filterSelect = document.getElementById('filterTipo');

  function filterCards() {
    const q    = searchInput.value.toLowerCase();
    const tipo = filterSelect.value;
    cards.forEach(card => {
      const title    = card.querySelector('.account-card__title').textContent.toLowerCase();
      const cardTipo = card.getAttribute('data-tipo');
      const matchesText = title.includes(q);
      const matchesTipo = !tipo || cardTipo === tipo;
      card.style.display = (matchesText && matchesTipo) ? '' : 'none';
    });
  }
  searchInput.addEventListener('input', filterCards);
  filterSelect.addEventListener('change', filterCards);

  // 4) Accordion: expande/retrai detalhes ao clicar no card
  cards.forEach(card => {
    card.addEventListener('click', () => {
      card.classList.toggle('expanded');
    });
  });
});
