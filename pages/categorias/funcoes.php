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
?>