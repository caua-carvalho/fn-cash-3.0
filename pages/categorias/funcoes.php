<?php
// Incluir conexão com o banco de dados
require_once dirname(__FILE__, 3) . "/conexao.php";

/**
 * Obtém todas as categorias do usuário atual
 * @return array Lista de categorias
 */
function obterCategorias($tipo = 'todos', $status = 'todos', $busca = '') {
    global $conn;

    // Verifique se a sessão está ativa
    if (!isset($_SESSION['id_usuario'])) {
        return array();
    }

    $idUsuario = $_SESSION['id_usuario'];

    $sql = "SELECT * FROM CATEGORIA WHERE ID_Usuario = ?";
    $tipos = "i";
    $params = [$idUsuario];

    if ($tipo !== 'todos') {
        $sql .= " AND Tipo = ?";
        $tipos .= "s";
        $params[] = $tipo;
    }

    if ($status !== 'todos') {
        $sql .= " AND Ativa = ?";
        $tipos .= "i";
        $params[] = ($status === 'ativas') ? 1 : 0;
    }

    if ($busca !== '') {
        $sql .= " AND Nome LIKE ?";
        $tipos .= "s";
        $params[] = '%' . $busca . '%';
    }

    $sql .= " ORDER BY Ativa DESC, ID_Categoria DESC";

    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($tipos, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $categorias = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }

    return $categorias;
}

/**
 * Cadastra uma nova categoria
 * @param string $nome Nome da categoria
 * @param string $tipo Tipo (Receita/Despesa)
 * @param string $descricao Descrição opcional
 * @param int $status Status (1=ativo, 0=inativo)
 * @return int|bool ID da categoria inserida ou false em caso de erro
 */
function cadastrarCategoria($nome, $tipo, $descricao, $status) {
    global $conn;
    
    // Verifique se a sessão está ativa
    if (!isset($_SESSION['id_usuario'])) {
        return false;
    }
    
    // Validação básica dos dados
    if (empty($nome) || empty($tipo)) {
        return false;
    }
    
    // Sanitização dos valores
    $nome = trim($nome);
    $tipo = trim($tipo);
    $descricao = trim($descricao);
    $status = $status ? 1 : 0;
    $idUsuario = $_SESSION['id_usuario'];
    
    $sql = "INSERT INTO CATEGORIA (Nome, Tipo, Descricao, Ativa, ID_Usuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro ao preparar consulta: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("sssii", $nome, $tipo, $descricao, $status, $idUsuario);
    
    if ($stmt->execute()) {
        $novoId = $conn->insert_id;
        $stmt->close();
        return $novoId;
    } else {
        error_log("Erro ao cadastrar categoria: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

/**
 * Edita uma categoria existente
 * @param int $id ID da categoria
 * @param string $nome Novo nome
 * @param string $tipo Novo tipo
 * @param string $descricao Nova descrição
 * @param int $status Novo status
 * @return bool Sucesso da operação
 */
function editarCategoria($id, $nome, $tipo, $descricao, $status) {
    global $conn;
    
    // Verifique se a sessão está ativa
    if (!isset($_SESSION['id_usuario'])) {
        return false;
    }
    
    // Validação básica dos dados
    if (empty($id) || empty($nome) || empty($tipo)) {
        return false;
    }
    
    // Sanitização dos valores
    $id = (int)$id;
    $nome = trim($nome);
    $tipo = trim($tipo);
    $descricao = trim($descricao);
    $status = $status ? 1 : 0;
    $idUsuario = $_SESSION['id_usuario'];
    
    // Verificar se a categoria pertence ao usuário
    $checkSql = "SELECT ID_Categoria FROM CATEGORIA WHERE ID_Categoria = ? AND ID_Usuario = ?";
    $checkStmt = $conn->prepare($checkSql);
    
    if (!$checkStmt) {
        error_log("Erro ao preparar verificação: " . $conn->error);
        return false;
    }
    
    $checkStmt->bind_param("ii", $id, $idUsuario);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        $checkStmt->close();
        return false; // Categoria não pertence ao usuário
    }
    
    $checkStmt->close();
    
    // Atualizar a categoria
    $sql = "UPDATE CATEGORIA SET Nome = ?, Tipo = ?, Descricao = ?, Ativa = ? WHERE ID_Categoria = ? AND ID_Usuario = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro ao preparar atualização: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("sssiii", $nome, $tipo, $descricao, $status, $id, $idUsuario);
    
    $resultado = $stmt->execute();
    $stmt->close();
    
    return $resultado;
}

/**
 * Exclui uma categoria
 * @param int $id ID da categoria
 * @return bool Sucesso da operação
 */
function deletarCategoria($id) {
    global $conn;
    
    // Verifique se a sessão está ativa
    if (!isset($_SESSION['id_usuario'])) {
        return false;
    }
    
    // Validação e sanitização
    $id = (int)$id;
    $idUsuario = $_SESSION['id_usuario'];
    
    // Verificar se existem transações vinculadas à categoria
    if (!podeDeletarCategoria($id)) {
        return false;
    }
    
    // Verificar se a categoria pertence ao usuário
    $checkSql = "SELECT ID_Categoria FROM CATEGORIA WHERE ID_Categoria = ? AND ID_Usuario = ?";
    $checkStmt = $conn->prepare($checkSql);
    
    if (!$checkStmt) {
        error_log("Erro ao preparar verificação: " . $conn->error);
        return false;
    }
    
    $checkStmt->bind_param("ii", $id, $idUsuario);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        $checkStmt->close();
        return false; // Categoria não pertence ao usuário
    }
    
    $checkStmt->close();
    
    // Excluir a categoria
    $sql = "DELETE FROM CATEGORIA WHERE ID_Categoria = ? AND ID_Usuario = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro ao preparar exclusão: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("ii", $id, $idUsuario);
    
    $resultado = $stmt->execute();
    $stmt->close();
    
    return $resultado;
}

/**
 * Verifica se uma categoria pode ser excluída com segurança
 * @param int $idCategoria ID da categoria
 * @return bool True se for seguro excluir, False caso contrário
 */
function podeDeletarCategoria($idCategoria) {
    global $conn;
    
    // Validação e sanitização
    $idCategoria = (int)$idCategoria;
    
    // Verifica se existem transações vinculadas a esta categoria
    $sql = "SELECT COUNT(*) as total FROM TRANSACAO WHERE ID_Categoria = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro ao preparar verificação: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return ($row['total'] == 0);
}
?>
