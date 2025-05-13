<?php
// Incluir conexão com o banco de dados
require_once dirname(__FILE__, 3) . "/conexao.php";

// Verifica se a sessão já está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Obtém todas as contas do usuário atual
 * @return array Lista de contas
 */
function obterContas() {
    global $conn;
    
    // Verifique se a sessão está ativa
    if (!isset($_SESSION['id_usuario'])) {
        return array();
    }
    
    $idUsuario = $_SESSION['id_usuario'];
    $sql = "SELECT * FROM CONTA WHERE ID_Usuario = ? ORDER BY Nome ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $contas = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contas[] = $row;
        }
    }
    
    return $contas;
}

/**
 * Cadastra uma nova conta
 * @param string $nome Nome da conta
 * @param string $tipo Tipo (Corrente/Poupança/etc)
 * @param float $saldo Saldo inicial
 * @param string $instituicao Nome da instituição financeira
 * @return int|bool ID da conta inserida ou false em caso de erro
 */
function cadastrarConta($nome, $tipo, $saldo, $instituicao) {
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
    $instituicao = trim($instituicao);
    $saldo = floatval($saldo);
    $idUsuario = $_SESSION['id_usuario'];
    $dataCriacao = date('Y-m-d');
    
    $sql = "INSERT INTO CONTA (Nome, Tipo, Saldo, Instituicao, DataCriacao, ID_Usuario) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro ao preparar consulta: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("ssdssi", $nome, $tipo, $saldo, $instituicao, $dataCriacao, $idUsuario);
    
    if ($stmt->execute()) {
        $novoId = $conn->insert_id;
        $stmt->close();
        return $novoId;
    } else {
        error_log("Erro ao cadastrar conta: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

/**
 * Edita uma conta existente
 * @param int $id ID da conta
 * @param string $nome Novo nome
 * @param string $tipo Novo tipo
 * @param float $saldo Novo saldo
 * @param string $instituicao Nova instituição
 * @return bool Sucesso da operação
 */
function editarConta($id, $nome, $tipo, $saldo, $instituicao) {
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
    $instituicao = trim($instituicao);
    $saldo = floatval($saldo);
    $idUsuario = $_SESSION['id_usuario'];
    
    // Verificar se a conta pertence ao usuário
    $checkSql = "SELECT ID_Conta FROM CONTA WHERE ID_Conta = ? AND ID_Usuario = ?";
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
        return false; // Conta não pertence ao usuário
    }
    
    $checkStmt->close();
    
    // Atualizar a conta
    $sql = "UPDATE CONTA SET Nome = ?, Tipo = ?, Saldo = ?, Instituicao = ? WHERE ID_Conta = ? AND ID_Usuario = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro ao preparar atualização: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("ssdsii", $nome, $tipo, $saldo, $instituicao, $id, $idUsuario);
    
    $resultado = $stmt->execute();
    $stmt->close();
    
    return $resultado;
}

/**
 * Exclui uma conta
 * @param int $id ID da conta
 * @return bool Sucesso da operação
 */
function deletarConta($id) {
    global $conn;
    
    // Verifique se a sessão está ativa
    if (!isset($_SESSION['id_usuario'])) {
        return false;
    }
    
    // Validação e sanitização
    $id = (int)$id;
    $idUsuario = $_SESSION['id_usuario'];
    
    // Verificar se a conta pertence ao usuário
    $checkSql = "SELECT ID_Conta FROM CONTA WHERE ID_Conta = ? AND ID_Usuario = ?";
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
        return false; // Conta não pertence ao usuário
    }
    
    $checkStmt->close();
    
    // Excluir a conta
    $sql = "DELETE FROM CONTA WHERE ID_Conta = ? AND ID_Usuario = ?";
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
 * Verifica se uma conta pode ser excluída com segurança
 * @param int $idConta ID da conta
 * @return bool True se for seguro excluir, False caso contrário
 */
function podeDeletarConta($idConta) {
    global $conn;
    
    // Validação e sanitização
    $idConta = (int)$idConta;
    
    // Verifica se existem transações vinculadas a esta conta
    $sql = "SELECT COUNT(*) as total FROM TRANSACAO WHERE ID_ContaRemetente = ? OR ID_ContaDestinataria = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro ao preparar verificação: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("ii", $idConta, $idConta);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return ($row['total'] == 0);
}

/**
 * Obtém o ícone correspondente ao tipo de conta
 * @param string $tipo Tipo da conta
 * @return string Classe do ícone FontAwesome
 */
function obterIconeTipoConta($tipo) {
    switch ($tipo) {
        case 'Corrente':
            return 'fa-wallet';
        case 'Poupança':
            return 'fa-piggy-bank';
        case 'Cartão de Crédito':
            return 'fa-credit-card';
        case 'VR/VA':
            return 'fa-utensils';
        case 'Investimento':
            return 'fa-chart-line';
        case 'Dinheiro':
            return 'fa-money-bill-wave';
        default:
            return 'fa-university';
    }
}