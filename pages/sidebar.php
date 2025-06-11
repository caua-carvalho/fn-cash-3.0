<?php
$documento = pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME);
?>
<div class="d-flex">
  <div class="sidebar-container">
    <aside id="sidebar" class="sidebar sidebar-fixed sidebar-collapsed">
      <div class="sidebar-header">
        <h3 class="sidebar-brand">
          <img src="../logo/logo_escrita_light.svg" alt="Fn-Cash" class="sidebar-logo-escrita" id="logo-escrita">
          <img src="../logo/icone.svg" alt="Fn-Cash" class="sidebar-logo-icone" id="logo-icone">
        </h3>
        <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Alternar sidebar">
          <i class="fas fa-chevron-left"></i>
        </button>
      </div>
      <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
          <a href="dashboard.php" class="sidebar-nav-link <?php if ($documento == 'dashboard') echo 'active'; ?>" title="Dashboard">
            <i class="sidebar-nav-icon fas fa-chart-line"></i>
            <span class="nav-text">Dashboard</span>
          </a>
        </li>
        <li class="sidebar-nav-item">
          <a href="categorias.php" class="sidebar-nav-link <?php if ($documento == 'categorias') echo 'active'; ?>" title="Categorias">
            <i class="sidebar-nav-icon fas fa-tags"></i>
            <span class="nav-text">Categorias</span>
          </a>
        </li>
        <li class="sidebar-nav-item">
          <a href="contas.php" class="sidebar-nav-link <?php if ($documento == 'contas') echo 'active'; ?>" title="Contas">
            <i class="sidebar-nav-icon fas fa-credit-card"></i>
            <span class="nav-text">Contas</span>
          </a>
        </li>
        <li class="sidebar-nav-item">
          <a href="transacoes.php" class="sidebar-nav-link <?php if ($documento == 'transacoes') echo 'active'; ?>" title="Transações">
            <i class="sidebar-nav-icon fas fa-money-bill-wave"></i>
            <span class="nav-text">Transações</span>
          </a>
        </li>
        <li class="sidebar-nav-item">
          <a href="config.php" class="sidebar-nav-link <?php if ($documento == 'config') echo 'active'; ?>" title="Configurações">
            <i class="sidebar-nav-icon fas fa-cog"></i>
            <span class="nav-text">Configurações</span>
          </a>
        </li>
        <!-- Perfil na sidebar, logo abaixo de Configurações -->
        <li class="sidebar-nav-item sidebar-profile-parent" style="position: relative;">
          <a href="javascript:void(0);" id="sidebar-profile-btn" class="sidebar-nav-link" title="Perfil" style="display: flex; align-items: center; justify-content: space-between;">
            <span style="display: flex; align-items: center;">
              <i class="sidebar-nav-icon fas fa-user-circle"></i>
              <span class="nav-text">Perfil</span>
            </span>
            <i class="fas fa-chevron-up" id="profile-dropdown-arrow" style="transition: transform 0.2s;"></i>
          </a>
          <div id="sidebar-profile-dropdown" class="sidebar-profile-dropdown" style="display: none; background: var(--color-surface); box-shadow: var(--shadow-md); border-radius: var(--border-radius-md); position: fixed; z-index: 1100; min-width: 180px;">
            <a href="config.php#dados-pessoais" class="sidebar-profile-link" style="display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: var(--color-text); transition: background 0.2s;">
              <i class="fas fa-user" style="margin-right: 8px;"></i> Meu Perfil
            </a>
            <a href="#" id="sidebar-logout-link" class="sidebar-profile-link" style="display: flex; align-items: center; padding: 12px 20px; text-decoration: none; color: var(--color-text); transition: background 0.2s;">
              <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i> Logout
            </a>
          </div>
        </li>
      </ul>
      <div class="sidebar-divider"></div>
      <!-- Removido o perfil fixo externo -->
    </aside>
  </div>
  <main id="main-content" class="main-content transition">

  <!-- Modal de confirmação de logout -->
  <div id="logout-modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.25); align-items:center; justify-content:center;">
    <div style="background:var(--color-surface,#fff); border-radius:8px; box-shadow:0 2px 16px rgba(0,0,0,0.18); min-width:320px; max-width:90vw; padding:28px 24px; display:flex; flex-direction:column; align-items:center;">
      <div style="font-size:1.2rem; font-weight:500; margin-bottom:18px; text-align:center;">
        Tem certeza que deseja sair?
      </div>
      <div style="display:flex; gap:16px; margin-top:8px;">
        <button id="logout-cancel-btn" style="padding:8px 18px; border:none; border-radius:4px; background:#eee; color:#333; font-weight:500; cursor:pointer;">Cancelar</button>
        <button id="logout-confirm-btn" style="padding:8px 18px; border:none; border-radius:4px; background:#d32f2f; color:#fff; font-weight:500; cursor:pointer;">Sair</button>
      </div>
    </div>
  </div>

<script>
// Gerado pelo Copilot
function atualizarLogosTema() {
  const body = document.body;
  const logoEscrita = document.getElementById('logo-escrita');
  const logoIcone = document.getElementById('logo-icone');

  if (!logoEscrita || !logoIcone) return;

  const temaClaro = body.classList.contains('light-theme');
  logoEscrita.src = temaClaro ? '../logo/logo_escrita_light.svg' : '../logo/logo_escrita.svg';
  logoIcone.src   = temaClaro ? '../logo/icone_light.svg'        : '../logo/icone.svg';
}

// Observa mudanças de classe no body pra detectar troca de tema
const observer = new MutationObserver(atualizarLogosTema);
observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });

// Atualiza logo ao carregar a página também
document.addEventListener('DOMContentLoaded', atualizarLogosTema);

// Dropdown de perfil na sidebar
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('sidebar-profile-btn');
  const dropdown = document.getElementById('sidebar-profile-dropdown');
  const arrow = document.getElementById('profile-dropdown-arrow');
  const sidebar = document.getElementById('sidebar');
  let dropdownTimeout;

  function openDropdown() {
    // Calcula a posição do botão em relação à viewport
    const btnRect = btn.getBoundingClientRect();
    // Usa o próprio btn como referência para largura e posição, não depende do estado da sidebar
    dropdown.style.display = 'block';
    dropdown.style.position = 'fixed';
    dropdown.style.left = btnRect.left + 'px';
    dropdown.style.top = (btnRect.bottom) + 'px';
    dropdown.style.width = btnRect.width + 'px';
    dropdown.style.zIndex = '1100';
    arrow.style.transform = 'rotate(180deg)';
  }

  function closeDropdown() {
    dropdown.style.display = 'none';
    arrow.style.transform = 'rotate(0deg)';
  }

  if (btn && dropdown && arrow) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = dropdown.style.display === 'block';
      if (isOpen) {
        closeDropdown();
      } else {
        openDropdown();
      }
    });

    // Abrir ao passar o mouse
    btn.addEventListener('mouseenter', function() {
      clearTimeout(dropdownTimeout);
      openDropdown();
    });
    // Manter aberto se mouse está no dropdown
    dropdown.addEventListener('mouseenter', function() {
      clearTimeout(dropdownTimeout);
      openDropdown();
    });
    // Suporte ao hover no item pai (funciona colapsado)
    const sidebarProfileParent = btn.closest('.sidebar-profile-parent');
    if (sidebarProfileParent) {
      sidebarProfileParent.addEventListener('mouseenter', function() {
        clearTimeout(dropdownTimeout);
        openDropdown();
      });
      sidebarProfileParent.addEventListener('mouseleave', function() {
        dropdownTimeout = setTimeout(closeDropdown, 180);
      });
    }
    // Fechar ao sair do botão ou dropdown (com pequeno delay para facilitar navegação)
    btn.addEventListener('mouseleave', function() {
      dropdownTimeout = setTimeout(closeDropdown, 180);
    });
    dropdown.addEventListener('mouseleave', function() {
      dropdownTimeout = setTimeout(closeDropdown, 180);
    });

    // Fecha dropdown ao clicar fora
    document.addEventListener('click', function(e) {
      if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        closeDropdown();
      }
    });
    // Fecha dropdown ao redimensionar janela ou rolar
    window.addEventListener('resize', closeDropdown);
    window.addEventListener('scroll', closeDropdown);
  }

  // Modal de confirmação de logout
  const logoutLink = document.getElementById('sidebar-logout-link');
  const logoutModal = document.getElementById('logout-modal');
  const logoutCancelBtn = document.getElementById('logout-cancel-btn');
  const logoutConfirmBtn = document.getElementById('logout-confirm-btn');

  if (logoutLink && logoutModal && logoutCancelBtn && logoutConfirmBtn) {
    logoutLink.addEventListener('click', function(e) {
      e.preventDefault();
      logoutModal.style.display = 'flex';
    });
    logoutCancelBtn.addEventListener('click', function() {
      logoutModal.style.display = 'none';
    });
    logoutConfirmBtn.addEventListener('click', function() {
      window.location.href = 'logout.php';
    });
    // Fecha modal ao clicar fora do conteúdo
    logoutModal.addEventListener('click', function(e) {
      if (e.target === logoutModal) {
        logoutModal.style.display = 'none';
      }
    });
    // ESC fecha o modal
    document.addEventListener('keydown', function(e) {
      if (logoutModal.style.display === 'flex' && e.key === 'Escape') {
        logoutModal.style.display = 'none';
      }
    });
  }
});
</script>
