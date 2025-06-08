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
          <a href="perfil.php" class="sidebar-nav-link <?php if ($documento == 'perfil') echo 'active'; ?>" title="Perfil">
            <i class="sidebar-nav-icon fas fa-user-circle"></i>
            <span class="nav-text">Perfil</span>
          </a>
        </li>
      </ul>
      <div class="sidebar-divider"></div>
    </aside>
  </div>
  <main id="main-content" class="main-content transition">
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
</script>
