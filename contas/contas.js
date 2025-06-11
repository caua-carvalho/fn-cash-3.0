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
  const cards        = document.querySelectorAll('.account-card');

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
});
