<?php
include '../../conexao.php';

function obterSaldoTotal(){
    global $conn;
    $sql = "SELECT SUM(Saldo) AS saldo_total FROM CONTA";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return 'R$ ' . $row['saldo_total']; // Retorna diretamente o saldo total
    }

    return 0; // Retorna 0 caso não haja resultados
}


?>