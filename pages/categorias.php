<?php
require_once 'header.php';
require_once 'nav.php';
require_once 'categorias/funcoes.php';
?>
<div class="container">
    <h2>Categorias</h2>
    
    <!-- Botão para cadastrar nova categoria -->
    <div class="mb-3">
        <a href="cadastrar_categoria.php" class="btn btn-success">Cadastrar Nova Categoria</a>
    </div>

    <!-- Tabela para exibir as categorias -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $categorias = obterCategorias();
            foreach ($categorias as $categoria) {
                echo "<tr>
                     <td>" . htmlspecialchars($categoria['Nome']) . "</td>
                     <td><span style='background-color: " . ($categoria['Tipo'] === 'Receita' ? 'green' : 'red') . "; color: white; padding: 2px 8px; border-radius: 5px; font-size: 12px;'>" . ($categoria['Tipo'] === 'receita' ? 'Receita' : 'Despesa') . "</span></td>
                     <td>" . ($categoria['Ativa'] ? 'Ativa' : 'Desativada') . "</td>
                     <td>
                     <a href='editar_categoria.php?id=" . $categoria['ID_Categoria'] . "' class='btn btn-primary btn-sm'>Editar</a> 
                     <a href='desativar_categoria.php?id=" . $categoria['ID_Categoria'] . "' class='btn btn-danger btn-sm'>Desativar</a>
                     </td>
                     </tr>";
            }
            ?>
        </tbody>
    </table>
</div>