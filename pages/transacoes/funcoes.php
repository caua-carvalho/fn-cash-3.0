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

function cadastrarTransacao($titulo, $descricao, $valor, $data, $tipo, $status, $idConta, $idCategoria = null, $idContaDestino = null) {
    global $conn;
    $sql = "INSERT INTO TRANSACAO (Titulo, Descricao, Valor, Data, DataRegistro, Tipo, Status, ID_Conta, ID_Categoria, ID_ContaDestino) 
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssiii", $titulo, $descricao, $valor, $data, $tipo, $status, $idConta, $idCategoria, $idContaDestino);

    if ($stmt->execute()) {
        return $conn->insert_id; // Retorna o ID da nova transação inserida
    } else {
        return false; // Retorna false em caso de falha
    }
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