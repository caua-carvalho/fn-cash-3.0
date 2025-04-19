<?php
// Incluir o cabeçalho
require_once "../conexao.php";

function obterContas() {
    global $conn;
    $sql = "SELECT * FROM CONTA ORDER BY ID_Conta ASC";
    $result = $conn->query($sql);
    $contas = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contas[] = $row;
        }
    }
    return $contas;
}

function cadastrarConta($nome, $tipo, $saldo, $instituicao) {
    global $conn;
    $sql = "INSERT INTO CONTA (Nome, Tipo, Saldo, Instituicao, DataCriacao, ID_Usuario) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $nome, $tipo, $saldo, $instituicao, $_SESSION['id_usuario']);

    if ($stmt->execute()) {
        return $conn->insert_id; // Retorna o ID da nova conta inserida
    } else {
        return false; // Retorna false em caso de falha
    }
}

function editarConta($id, $nome, $tipo, $saldo, $instituicao) {
    global $conn;
    $sql = "UPDATE CONTA SET Nome = ?, Tipo = ?, Saldo = ?, Instituicao = ? WHERE ID_Conta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $nome, $tipo, $saldo, $instituicao, $id);

    return $stmt->execute(); // Retorna true em caso de sucesso ou false em caso de falha
}

function deletarConta($id) {
    global $conn;
    $sql = "DELETE FROM CONTA WHERE ID_Conta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute(); // Retorna true em caso de sucesso ou false em caso de falha
}
?>