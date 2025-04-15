<?php
require_once '../conexao.php';

function obterOrcamentos() {
    global $conn;
    $sql = "SELECT o.*, c.Nome AS NomeCategoria 
            FROM ORCAMENTO o
            JOIN CATEGORIA c ON o.ID_Categoria = c.ID_Categoria";
    $result = $conn->query($sql);
    $orcamentos = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orcamentos[] = $row;
        }
    }
    return $orcamentos;
}

function cadastrarOrcamento($idCategoria, $valorPlanejado, $periodo, $status) {
    global $conn;
    $sql = "INSERT INTO ORCAMENTO (ID_Categoria, ValorPlanejado, Periodo, Ativo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $idCategoria, $valorPlanejado, $periodo, $status);

    return $stmt->execute();
}

function editarOrcamento($id, $idCategoria, $valorPlanejado, $periodo, $status) {
    global $conn;
    $sql = "UPDATE ORCAMENTO SET ID_Categoria = ?, ValorPlanejado = ?, Periodo = ?, Ativo = ? WHERE ID_Orcamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idssi", $idCategoria, $valorPlanejado, $periodo, $status, $id);

    return $stmt->execute();
}

function deletarOrcamento($id) {
    global $conn;
    $sql = "DELETE FROM ORCAMENTO WHERE ID_Orcamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

function obterCategorias() {
    global $conn; // Certifique-se de que a conexão com o banco de dados está configurada
    $sql = "SELECT ID_Categoria, Nome FROM CATEGORIA";
    $result = $conn->query($sql);
    $categorias = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }

    return $categorias;
}
?>