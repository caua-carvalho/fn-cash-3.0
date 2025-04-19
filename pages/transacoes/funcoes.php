<?php
// Incluir o cabeçalho
require_once "../conexao.php";

function obterTransacoes() {
    global $conn;
    $sql = "SELECT 
                t.*, 
                cr.ID_Conta AS ID_ContaRemetente, 
                cr.Nome AS NomeContaRemetente, 
                cd.ID_Conta AS ID_ContaDestinataria, 
                cd.Nome AS NomeContaDestinataria
            FROM TRANSACAO t
            LEFT JOIN CONTA cr ON t.ID_ContaRemetente = cr.ID_Conta
            LEFT JOIN CONTA cd ON t.ID_ContaDestinataria = cd.ID_Conta
            ORDER BY t.Data DESC";
    $result = $conn->query($sql);
    $transacoes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transacoes[] = $row;
        }
    }
    return $transacoes;
}

function obterSaldoConta($idConta) {
    global $conn;
    $sql = "SELECT Saldo FROM CONTA WHERE ID_Conta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idConta);
    $stmt->execute();
    $stmt->bind_result($saldo);
    $stmt->fetch();
    $stmt->close();

    return $saldo;
}

function cadastrarTransacao($id_usuario, $titulo, $descricao, $valor, $formaPagamento, $data, $tipo, $status, $idCategoria, $idContaRemetente, $idContaDestinataria = null) {
    global $conn;

    // Converte os valores para inteiros
    $idContaRemetente = intval($idContaRemetente);
    $idContaDestinataria = !empty($idContaDestinataria) ? intval($idContaDestinataria) : null;

    // Verifica se as contas são iguais em caso de transferência
    if ($tipo === 'Transferência' && $idContaRemetente === $idContaDestinataria) {
        erro('Conta remetente e destinatária não podem ser iguais.');
        exit;
    }

    // Verifica se a conta remetente existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM CONTA WHERE ID_Conta = ?");
    $stmt->bind_param("i", $idContaRemetente);
    $stmt->execute();
    $stmt->bind_result($contaRemetenteExiste);
    $stmt->fetch();
    $stmt->close();

    if ($contaRemetenteExiste === 0) { // Verifica se a conta não existe
        echo '<script>alert("Conta remetente inválida. ID = ' . htmlspecialchars($idContaRemetente) . '")</script>';
        exit;
    }

    // Verifica saldo para despesas ou transferências
    if (($tipo === 'Despesa' || $tipo === 'Transferência') && obterSaldoConta($idContaRemetente) < $valor) {
        erro("Saldo insuficiente na conta remetente.");
        exit;
    }

    // Query SQL para inserir a transação
    $sql = "INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_ContaDestinataria, ID_Usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a declaração SQL: " . $conn->error);
    }

    $stmt->bind_param(
        "ssdssssiiii",
        $titulo,
        $descricao,
        $valor,
        $formaPagamento,
        $data,
        $tipo,
        $status,
        $idContaRemetente,
        $idCategoria,
        $idContaDestinataria,
        $id_usuario
    );

    if (!$stmt->execute()) {
        die("Erro ao executar a declaração SQL: " . $stmt->error);
    }

    return true;
}

function editarTransacao($ID_Usuario, $titulo, $descricao, $valor, $data, $tipo, $status, $idCategoria, $ID_ContaRemetente, $ID_ContaDestinataria, $ID_Transacao) {
    global $conn;
    $sql = "UPDATE TRANSACAO SET Titulo = ?, Descricao = ?, Valor = ?, Data = ?, Tipo = ?, Status = ?, ID_Categoria = ?, ID_ContaDestinataria = ?, ID_ContaRemetente = ?, ID_Usuario = ? 
            WHERE ID_Transacao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssiiiii", $titulo, $descricao, $valor, $data, $tipo, $status, $idCategoria, $ID_ContaDestinataria, $ID_ContaRemetente, $ID_Usuario, $ID_Transacao);

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