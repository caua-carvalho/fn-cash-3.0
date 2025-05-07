/**
 * Controle da sidebar para o FnCash
 * Gerencia o estado de abertura/fechamento e o tema
 */
document.addEventListener('DOMContentLoaded', function() {
  // Elementos DOM
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebar-toggle');
  const mainContent = document.getElementById('main-content');
  const themeToggle = document.getElementById('toggle-theme');
  
  // Inicializa o estado da sidebar
  function inicializar_sidebar() {
    // Verifica se existe um estado salvo
    const sidebarFechada = localStorage.getItem('sidebarFechada') === 'true';
    
    if (sidebarFechada) {
      sidebar.classList.add('sidebar-collapsed');
    }
    
    // Verifica o tamanho da tela para ajustes automáticos
    ajustar_para_tamanho_tela();
  }
  
  // Alterna o estado da sidebar
  function alternar_sidebar() {
    sidebar.classList.toggle('sidebar-collapsed');
    
    // Salva o estado atual
    const fechada = sidebar.classList.contains('sidebar-collapsed');
    localStorage.setItem('sidebarFechada', fechada);
  }
  
  // Ajusta a sidebar com base no tamanho da tela
  function ajustar_para_tamanho_tela() {
    const larguraTela = window.innerWidth;
    
    if (larguraTela < 992 && !sidebar.classList.contains('sidebar-collapsed') && larguraTela >= 768) {
      sidebar.classList.add('sidebar-collapsed');
    }
    
    if (larguraTela < 768) {
      sidebar.classList.remove('sidebar-collapsed');
    }
  }
  
  // Eventos
  sidebarToggle.addEventListener('click', alternar_sidebar);
  themeToggle.addEventListener('click', alternar_tema);
  window.addEventListener('resize', ajustar_para_tamanho_tela);
  
  // Inicialização
  inicializar_sidebar();
  
  // Carrega o tema salvo (se existir)
  const temaSalvo = localStorage.getItem('tema');
  if (temaSalvo) {
    const body = document.body;
    body.classList.remove('light-theme', 'dark-theme');
    body.classList.add(`${temaSalvo}-theme`);
    
    themeToggle.innerHTML = `<i class="fas fa-${temaSalvo === 'dark' ? 'moon' : 'sun'} me-2"></i>
                            <span class="nav-text">Tema ${temaSalvo === 'dark' ? 'Escuro' : 'Claro'}</span>`;
  }
});