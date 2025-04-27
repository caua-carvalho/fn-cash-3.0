<?phpcontainer

?>

<div class="wrapper">
    <nav id="sidebar">
        <button class="menu-toggle" id="toggleSidebar">☰</button>
        <ul>
            <li>
                <a href="categorias.php" class="text-white icone-sidebar <?php if ($documento == 'categorias') echo 'active'; ?>">
                    <i class="bi bi-tags"></i>
                    <span>Categorias</span>
                </a>
            </li>
            <li>
                <a href="contas.php" class="text-white icone-sidebar <?php if ($documento == 'contas') echo 'active'; ?>">
                    <i class="bi bi-wallet2"></i>
                    <span>Contas</span>
                </a>
            </li>
            <li>
                <a href="transacoes.php" class="text-white icone-sidebar <?php if ($documento == 'transacoes') echo 'active'; ?>">
                    <i class="bi bi-shuffle"></i>
                    <span>Transacoes</span>
                </a>
            </li>
            <li>
                <a href="orcamento.php" class="text-white icone-sidebar <?php if ($documento == 'orcamento') echo 'active'; ?>">
                    <i class="bi bi-graph-up"></i>
                    <span>Orcamentos</span>
                </a>
            </li>
        </ul>
    </nav>

    <div id="main-content">
