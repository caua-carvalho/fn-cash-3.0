<?php
// Incluir o cabeçalho
require_once "../conexao.php";

function obterTransacoes() {
    global $conn;
    $sql = "SELECT * FROM TRANSACAO ORDER BY ID_Transacao ASC";
    $result = $conn->query($sql);
    $transacoes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transacoes[] = $row;
        }
    }
    return $transacoes;
}

function cadastrarTransacao($id_usuario, $titulo, $descricao, $valor, $data, $tipo, $status, $idCategoria, $idContaRemetente, $idContaDestinataria) {
    global $conn;

    // Converte os valores para os tipos corretos
    $idCategoria = intval($idCategoria);
    $valor = floatval($valor);
    $idContaRemetente = intval($idContaRemetente);
    $idContaDestinataria = intval($idContaDestinataria);

    // Query SQL
    $sql = "INSERT INTO TRANSACAO (Titulo, Descricao, Valor, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_ContaDestinataria, ID_Usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara a declaração
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a declaração SQL: " . $conn->error);
    }

    // Vincula os parâmetros
    if (!$stmt->bind_param("ssdsssiiii", $titulo, $descricao, $valor, $data, $tipo, $status, $idContaRemetente, $idCategoria, $idContaDestinataria, $id_usuario)) {
        die("Erro ao vincular parâmetros: " . $stmt->error);
    }

    // Executa a declaração
    if (!$stmt->execute()) {
        die("Erro ao executar a declaração SQL: " . $stmt->error);
    }

    return true; // Retorna true em caso de sucesso
}

function editarTransacao($id, $titulo, $descricao, $valor, $data, $tipo, $status, $idConta, $idCategoria = null, $idContaDestino = null) {
    global $conn;
    $sql = "UPDATE TRANSACAO SET Titulo = ?, Descricao = ?, Valor = ?, Data = ?, Tipo = ?, Status = ?, ID_Conta = ?, ID_Categoria = ?, ID_ContaDestino = ? 
            WHERE ID_Transacao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssiiii", $titulo, $descricao, $valor, $data, $tipo, $status, $idConta, $idCategoria, $idContaDestino, $id);

    return $stmt->execute(); // Retorna true em caso de sucesso ou false em caso de falha
}

function deletarTransacao($id) {
    global $conn;
    $sql = "DELETE FROM TRANSACAO WHERE ID_Transacao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute(); // Retorna true em caso de sucesso ou false em caso de falha
}
?>