<?php
$documento = pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME);
?>
<div class="d-flex">
  <aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
      <h3 class="sidebar-brand">
        <img src="../logo/logo_escrita.svg" alt="Fn-Cash" class="max-width">
      </h3>
      <button id="sidebar-toggle" class="sidebar-toggle">
        <i class="fas fa-chevron-left"></i>
      </button>
    </div>
    
    <ul class="sidebar-nav">
      <li class="sidebar-nav-item">
        <a href="dashboard.php" class="sidebar-nav-link <?php if ($documento == 'dashboard') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-chart-line"></i>
          <span class="nav-text">Dashboard</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item">
        <a href="categorias.php" class="sidebar-nav-link <?php if ($documento == 'categorias') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-tags"></i>
          <span class="nav-text">Categorias</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item">
        <a href="contas.php" class="sidebar-nav-link <?php if ($documento == 'contas') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-credit-card"></i>
          <span class="nav-text">Contas</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item">
        <a href="transacoes.php" class="sidebar-nav-link <?php if ($documento == 'transacoes') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-money-bill-wave"></i>
          <span class="nav-text">Transações</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item">
        <a href="perfil.php" class="sidebar-nav-link <?php if ($documento == 'perfil') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-user-circle"></i>
          <span class="nav-text">Perfil</span>
        </a>
      </li>
    </ul>
    
    <div class="sidebar-divider"></div>
  </aside>
  
  <main id="main-content" class="main-content transition">