<?php
// Incluir o cabeçalho
require_once "../conexao.php";

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

function cadastrarCategoria($nome, $tipo, $descricao) {
    global $conn;
    $sql = "INSERT INTO CATEGORIA (Nome, Tipo, Descricao, Ativa, ID_Usuario) VALUES (?, ?, ?, 1, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $tipo, $descricao, $_SESSION['id']);

    if ($stmt->execute()) {
        return $conn->insert_id; // Retorna o ID da nova categoria inserida
    } else {
        return false; // Retorna false em caso de falha
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