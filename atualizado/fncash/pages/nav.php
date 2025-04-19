

<?php
require_once 'header.php';
?>

<body>
  <div class="sidebar">
    <div class="logo_details">
      <i class="bx bxl-audible icon"></i>
      <div class="logo_name">FN Cash</div>
      <i class="bx bx-menu" id="btn"></i>
    </div>
    <ul class="nav-list">
      <li>
        <a href="dashboard.php">
          <i class="bx bx-grid-alt"></i>
          <span class="link_name">Dashboard</span>
        </a>
        <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <a href="transacoes.php">
          <i class='bx bx-transfer'></i>
          <span class="link_name">Transações</span>
        </a>
        <span class="tooltip">Transações</span>
      </li>
      <li>
        <a href="contas.php">
          <i class='bx bx-wallet'></i>
          <span class="link_name">Contas</span>
        </a>
        <span class="tooltip">Contas</span>
      </li>
      <li>
        <a href="orcamento.php">
          <i class="bx bx-pie-chart-alt-2"></i>
          <span class="link_name">Orçamentos</span>
        </a>
        <span class="tooltip">Orçamentos</span>
      </li>
      <li>
        <a href="metas.php">
         <i class='bx bx-bullseye'></i>
          <span class="link_name">Metas</span>
        </a>
        <span class="tooltip">Metas</span>
      </li>
      <li>
        <a href="relatorios.php">
         <i class='bx bx-bar-chart-alt-2'></i>
          <span class="link_name">Relatórios</span>
        </a>
        <span class="tooltip">Relatórios</span>
      </li>
      <li>
        <a href="categorias.php">
         <i class='bx bx-purchase-tag-alt'></i>
          <span class="link_name">Categorias</span>
        </a>
        <span class="tooltip">Categorias</span>
      </li>
      <li>
        <a href="logout.php">
          <i class="bx bx-log-out"></i>
          <span class="link_name">Sair</span>
        </a>
        <span class="tooltip">Sair</span>
      </li>
      <li class="profile">
        <div class="profile_details">
          <img src="profile.jpeg" alt="profile image">
          <div class="profile_content">
            <div class="name">Anna Jhon</div>
            <div class="designation">Admin</div>
          </div>
        </div>
        <i class="bx bx-log-out" id="log_out"></i>
      </li>
    </ul>
  </div>
</body>
</html>