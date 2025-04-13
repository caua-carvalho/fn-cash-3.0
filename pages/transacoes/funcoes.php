<?php
// Incluir o cabeçalho
require_once "../conexao.php";

function obterTransacoes() {
    global $conn;
    $sql = "SELECT 
                t.ID_Transacao,
                t.Titulo,
                t.Descricao,
                t.Valor,
                t.Data,
                t.Tipo,
                t.Status,
                remetente.Nome AS ContaRemetente, -- Nome da conta remetente
                destinataria.Nome AS ContaDestinataria, -- Nome da conta destinatária
                t.ID_Categoria,
                t.ID_Usuario
            FROM 
                TRANSACAO t
            LEFT JOIN 
                CONTA remetente ON t.ID_ContaRemetente = remetente.ID_Conta
            LEFT JOIN 
                CONTA destinataria ON t.ID_ContaDestinataria = destinataria.ID_Conta
            ORDER BY 
                t.ID_Transacao ASC";
    $result = $conn->query($sql);
    $transacoes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transacoes[] = $row;
        }
    }
    return $transacoes;
}

function cadastrarTransacao($id_usuario, $titulo, $descricao, $valor, $data, $tipo, $status, $idCategoria, $idContaRemetente, $idContaDestinataria = null) {
    global $conn;

    if ($idContaRemetente == $idContaDestinataria && $tipo === 'Transferência') {
        erro('Conta remetente e destinatária não podem ser iguais.'); 
        exit;
    }

    // Converte os valores para os tipos corretos
    $idCategoria = intval($idCategoria);
    $valor = floatval($valor);
    $idContaRemetente = intval($idContaRemetente);
    $idContaDestinataria = !empty($idContaDestinataria) ? intval($idContaDestinataria) : null;

    // Verifica se a conta remetente existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM CONTA WHERE ID_Conta = ?");
    $stmt->bind_param("i", $idContaRemetente);
    $stmt->execute();
    $stmt->bind_result($contaRemetenteExiste);
    $stmt->fetch();
    $stmt->close();

    if (!$contaRemetenteExiste) {
        erro("Conta remetente inválida.");
        exit;
    }

    // Query SQL
    $sql = "INSERT INTO TRANSACAO (Titulo, Descricao, Valor, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_ContaDestinataria, ID_Usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara a declaração
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a declaração SQL: " . $conn->error);
    }

    // Vincula os parâmetros
    $stmt->bind_param(
        "ssdsssiiii",
        $titulo,
        $descricao,
        $valor,
        $data,
        $tipo,
        $status,
        $idContaRemetente,
        $idCategoria,
        $idContaDestinataria,
        $id_usuario
    );

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