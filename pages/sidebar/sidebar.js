/**
 * Refatoração: Sidebar com modo fixa, colapsada (hover) e responsiva
 * - Fixa: sempre aberta
 * - Colapsada: só ícones, expande ao hover
 * - Mobile: vira bottom bar
 */
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebar-toggle');
  const mainContent = document.getElementById('main-content');
  const themeToggle = document.getElementById('toggle-theme');

  let hoverHabilitado = true;
  let hoverTimeout = null;

  // Função para salvar estado da sidebar
  function salvarEstadoSidebar(colapsada) {
    localStorage.setItem('sidebarColapsada', colapsada);
  }

  // Função para alternar entre fixa e colapsada
  function alternarSidebar() {
    if (window.innerWidth <= 768) return; // No mobile, ignora
    
    sidebar.classList.toggle('sidebar-collapsed');
    const estaColapsada = sidebar.classList.contains('sidebar-collapsed');
    salvarEstadoSidebar(estaColapsada);
    ajustarMainContent();
    
    // Desabilita hover por 1s após toggle
    hoverHabilitado = false;
    if (hoverTimeout) clearTimeout(hoverTimeout);
    
    // Remove hover imediatamente ao colapsar
    sidebar.classList.remove('sidebar-hover');
    
    // Reabilita hover após 1 segundo
    hoverTimeout = setTimeout(() => {
      hoverHabilitado = true;
      // Se o mouse ainda estiver sobre a sidebar após 1s, reativa o hover
      if (sidebar.matches(':hover') && sidebar.classList.contains('sidebar-collapsed')) {
        sidebar.classList.add('sidebar-hover');
      }
    }, 1000);
  }

  // Ajusta o margin do main-content conforme sidebar
  function ajustarMainContent() {
    if (window.innerWidth > 768) {
      if (sidebar.classList.contains('sidebar-collapsed')) {
        mainContent.style.marginLeft = '80px';
      } else {
        mainContent.style.marginLeft = '260px';
      }
    } else {
      mainContent.style.marginLeft = '0';
    }
  }

  // Função para inicializar sidebar com estado salvo
  function inicializarSidebar() {
    if (window.innerWidth > 768) {
      // Pega o estado salvo, se não existir assume false (expandida)
      const colapsada = localStorage.getItem('sidebarColapsada');
      
      // Se tiver um estado salvo, aplica ele
      if (colapsada !== null) {
        sidebar.classList.toggle('sidebar-collapsed', colapsada === 'true');
      }
      
      // Ajusta o main content de acordo com o estado atual
      ajustarMainContent();
    }
  }

  // Responsividade: mobile sempre aberta (bottom bar)
  function onResize() {
    if (window.innerWidth <= 768) {
      sidebar.classList.remove('sidebar-collapsed');
      mainContent.style.marginLeft = '0';
    } else {
      inicializarSidebar();
    }
  }

  // Expande ao hover se colapsada (desktop)
  sidebar.addEventListener('mouseenter', function() {
    if (window.innerWidth > 768 && sidebar.classList.contains('sidebar-collapsed') && hoverHabilitado) {
      sidebar.classList.add('sidebar-hover');
    }
  });
  sidebar.addEventListener('mouseleave', function() {
    if (window.innerWidth > 768 && sidebar.classList.contains('sidebar-collapsed')) {
      sidebar.classList.remove('sidebar-hover');
    }
  });

  // Botão de toggle
  sidebarToggle.addEventListener('click', alternarSidebar);
  window.addEventListener('resize', onResize);
  
  // Adiciona event listener do tema apenas se o elemento existir
  if (themeToggle) {
    themeToggle.addEventListener('click', alternar_tema);
    
    // Carrega o tema salvo apenas se o toggle existir
    const temaSalvo = localStorage.getItem('tema');
    if (temaSalvo) {
      const body = document.body;
      body.classList.remove('light-theme', 'dark-theme');
      body.classList.add(`${temaSalvo}-theme`);

      themeToggle.innerHTML = `<i class="fas fa-${temaSalvo === 'dark' ? 'moon' : 'sun'} me-2"></i>
                              <span class="nav-text">Tema ${temaSalvo === 'dark' ? 'Escuro' : 'Claro'}</span>`;
    }
  }

  // Inicializa ao carregar
  inicializarSidebar();
});