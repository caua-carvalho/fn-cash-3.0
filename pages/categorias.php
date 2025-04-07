<?php
require_once 'header.php';
require_once 'nav.php';
require_once 'categorias/funcoes.php';
require_once 'categorias/script.php';
require_once 'categorias/modal.php';
require_once 'dialog.php';
?>

<div class="container">
    <h2>Categorias</h2>
    
    <!-- Botão para cadastrar nova categoria -->
    <div class="mb-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#categoriaModal">Cadastrar Nova Categoria</button>
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
                     <a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarCategoriaModal'
                        data-id='" . $categoria['ID_Categoria'] . "'
                        data-nome='" . htmlspecialchars($categoria['Nome']) . "'
                        data-tipo='" . $categoria['Tipo'] . "'
                        data-descricao='" . htmlspecialchars($categoria['Descricao']) . "'
                        data-status='" . ($categoria['Ativa'] ? 'true' : 'false') . "'>
                        Editar
                     </a> 
                     <a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#excluirCategoriaModal'
                        data-id='" . $categoria['ID_Categoria'] . "'>
                        Excluir
                     </a>
                     </td>
                     </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
require_once 'footer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se a ação foi definida
    $acao = $_POST['acao'] ?? null;

    if (!$acao) {
        erro("Ação não definida.");
        exit;
    }

    // Obtém os dados enviados
    $id = $_POST['categoriaId'] ?? null;
    $nome = $_POST['nomeCategoria'] ?? '';
    $tipo = $_POST['tipoCategoria'] ?? '';
    $descricao = $_POST['descricaoCategoria'] ?? '';
    $status = $_POST['statusCategoria'] ?? true;

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarCategoria':
            if (editarCategoria($id, $nome, $tipo, $descricao, $status)) {
                confirmar("Categoria editada com sucesso!", "categorias.php");
            } else {
                erro("Erro ao editar categoria. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarCategoria':
            if (cadastrarCategoria($nome, $tipo, $descricao, $status)) {
                confirmar("Categoria cadastrada com sucesso!", "categorias.php");
            } else {
                erro("Erro ao cadastrar categoria. Verifique os dados e tente novamente.");
            }
            break;
        case 'excluirCategoria':
            if (deletarCategoria($id)) {
                confirmar("Categoria excluída com sucesso!", "categorias.php");
            } else {
                erro("Erro ao excluir categoria. Verifique os dados e tente novamente.");
            }
            break;

        default:
            erro("Ação inválida.");
            break;
    }
}
