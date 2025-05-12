 <?php
// Incluir o cabeçalho
require_once "../conexao.php";
require_once "dialog.php";



function obterCategorias() {
    global $conn;
    $sql = "SELECT * FROM CATEGORIA ORDER BY Ativa DESC, ID_Categoria ASC";
    $result = $conn->query($sql);
    $categorias = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }
    return $categorias;
}

function cadastrarCategoria($nome, $tipo, $descricao, $status) {
    global $conn;

    $status = ($_POST['statusCategoria'] === 'true') ? 1 : 0;

    $sql = "INSERT INTO CATEGORIA (Nome, Tipo, Descricao, Ativa, ID_Usuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $nome, $tipo, $descricao, $status, $_SESSION['id_usuario']);

    if ($stmt->execute()) {
        return $conn->insert_id; // Retorna o ID da nova categoria inserida
    } else {
        return erro("Erro ao cadastrar categoria: " . $conn->error); // Retorna false em caso de falha
    }
}

function editarCategoria($id, $nome, $tipo, $descricao, $status) {
    global $conn;
    $sql = "UPDATE CATEGORIA SET Nome = ?, Tipo = ?, Descricao = ?, Ativa = ? WHERE ID_Categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $nome, $tipo, $descricao, $status, $id);

    return $stmt->execute(); // Retorna true em caso de sucesso ou false em caso de falha
}

function deletarCategoria($id) {
    global $conn;
    $sql = "DELETE FROM CATEGORIA WHERE ID_Categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute(); // Retorna true em caso de sucesso ou false em caso de falha
}
?>