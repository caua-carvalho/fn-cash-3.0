document.addEventListener('DOMContentLoaded', () => {
  // 1) Abre modais sem propagar o clique ao card
  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      const modal = new bootstrap.Modal(
        document.querySelector(btn.getAttribute('data-modal-open'))
      );
      modal.show();
    });
  });

  // 2) Filtragem de cards
  const searchInput  = document.getElementById('searchConta');
  const filterSelect = document.getElementById('filterTipo');
  const cards        = Array.from(document.querySelectorAll('.account-card'));

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

  // 3) Accordion: expande/retrai detalhes ao clicar no card
  cards.forEach(card => {
    card.addEventListener('click', () => {
      card.classList.toggle('expanded');
    });
  });

  // 4) Agrupamento visual por tipo de conta
  const grid = document.getElementById('contasGrid');
  if (grid) {
    const iconMap = {
      'Corrente': 'fa-university',
      'Poupança': 'fa-piggy-bank',
      'Cartão de Crédito': 'fa-credit-card',
      'Investimento': 'fa-chart-line',
      'Outros': 'fa-wallet'
    };

    const groups = {};
    cards.forEach(card => {
      const tipo = card.getAttribute('data-tipo') || 'Outros';

      // adiciona ícone ao card
      const header = card.querySelector('.account-card__header');
      if (header && !header.querySelector('.account-card__icon')) {
        const iconDiv = document.createElement('div');
        iconDiv.className = 'account-card__icon';
        iconDiv.innerHTML = `<i class="fas ${iconMap[tipo] || iconMap['Outros']}"></i>`;
        header.prepend(iconDiv);
      }

      if (!groups[tipo]) {
        const group = document.createElement('div');
        group.className = 'account-group';
        group.setAttribute('data-tipo', tipo);
        group.innerHTML = `
          <h4 class="account-group__title"><i class="fas ${iconMap[tipo] || iconMap['Outros']} me-2"></i>${tipo}</h4>
          <div class="account-group__list"></div>
        `;
        groups[tipo] = group;
      }
      groups[tipo].querySelector('.account-group__list').appendChild(card);
    });

    grid.innerHTML = '';
    Object.values(groups).forEach(group => grid.appendChild(group));
  }
});
