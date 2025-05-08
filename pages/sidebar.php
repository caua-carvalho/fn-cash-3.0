<?php
include_once 'header.php';
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
      <li class="sidebar-nav-item ">
        <a href="home.php" class="sidebar-nav-link <?php if ($documento == 'home') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-home"></i>
          <span class="nav-text">Home</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item ">
        <a href="categorias.php" class="sidebar-nav-link <?php if ($documento == 'categorias') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-th-large active"></i>
          <span class="nav-text">Categorias</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item ">
        <a href="contas.php" class="sidebar-nav-link <?php if ($documento == 'contas') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-wallet"></i>
          <span class="nav-text">Contas</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item ">
        <a href="transacoes.php" class="sidebar-nav-link <?php if ($documento == 'transacoes') echo 'active'; ?>">
          <i class="sidebar-nav-icon fas fa-exchange-alt"></i>
          <span class="nav-text">Transações</span>
        </a>
      </li>
      
      <li class="sidebar-nav-item <?php if ($documento == 'perfil') echo 'active'; ?>">
        <a href="perfil.php" class="sidebar-nav-link">
          <i class="sidebar-nav-icon fas fa-user"></i>
          <span class="nav-text">Perfil</span>
        </a>
      </li>
    </ul>
    
    <div class="sidebar-divider"></div>
  </aside>
  
  <main id="main-content" class="main-content transition">