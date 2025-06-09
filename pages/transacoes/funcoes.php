<?php
// Incluir o cabeçalho
require_once "../conexao.php";

// funcoes.php
/**
 * Retorna lista de transações do usuário, opcionalmente filtradas por data
 * @param string|null $dataInicio  Formato YYYY-MM-DD
 * @param string|null $dataFim     Formato YYYY-MM-DD
 * @return array
 */
function obterTransacoes(string $dataInicio = null, string $dataFim = null): array {
    global $conn;
    $idUsuario = $_SESSION['id_usuario'];

    // Monta SQL base
    $sql = "
      SELECT 
        t.*, 
        cr.ID_Conta        AS ID_ContaRemetente, 
        cr.Nome            AS NomeContaRemetente, 
        cd.ID_Conta        AS ID_ContaDestinataria, 
        cd.Nome            AS NomeContaDestinataria
      FROM TRANSACAO t
      LEFT JOIN CONTA cr ON t.ID_ContaRemetente   = cr.ID_Conta
      LEFT JOIN CONTA cd ON t.ID_ContaDestinataria = cd.ID_Conta
      WHERE t.ID_Usuario = ?
    ";
    $types  = "i";
    $params = [$idUsuario];

    // Filtro por datas (campo DATE ou VARCHAR 'YYYY-MM-DD')
    if ($dataInicio && $dataFim) {
        $sql   .= " AND t.Data BETWEEN ? AND ?";
        $types .= "ss";
        $params[] = $dataInicio;
        $params[] = $dataFim;
    }

    $sql .= " ORDER BY t.Id_Transacao DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();
    $transacoes = [];
    while ($row = $result->fetch_assoc()) {
        $transacoes[] = $row;
    }

    $sql .= " ORDER BY t.ID_Transacao DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        logErroConsole("Erro ao preparar consulta de transações: " . $conn->error);
        return [];
    }

    // Faz o bind dos parâmetros se houver filtros
    if (count($valores) > 0) {
        $stmt->bind_param(implode('', $tipos), ...$valores);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $transacoes = [];

    while ($row = $result->fetch_assoc()) {
        $transacoes[] = $row;
    }

    $stmt->close();
    return $transacoes;
}

function obterSaldoTipo($tipo): float {
    global $conn;

    $sql = "
      SELECT COALESCE(SUM(Valor), 0) AS valor
      FROM TRANSACAO
      WHERE ID_Usuario = ? 
        AND Tipo = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $_SESSION['id_usuario'], $tipo);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Aqui retorna só o número, não o array
    return $row['valor'];
}


function obterTransacoesPorId($id_transacao){
    global $conn;
    $sql = "SELECT 
                t.*, 
                t.ID_Usuario, 
                cr.ID_Conta AS ID_ContaRemetente, 
                cr.Nome AS NomeContaRemetente, 
                cd.ID_Conta AS ID_ContaDestinataria, 
                cd.Nome AS NomeContaDestinataria
            FROM TRANSACAO t
            LEFT JOIN CONTA cr ON t.ID_ContaRemetente = cr.ID_Conta
            LEFT JOIN CONTA cd ON t.ID_ContaDestinataria = cd.ID_Conta
            WHERE t.ID_Transacao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_transacao);
    $stmt->execute();
    $result = $stmt->get_result();
    $transacao = null;

    if ($result->num_rows > 0) {
        $transacao = $result->fetch_assoc();
    }

    return $transacao;
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

/**
 * Função para logar erro no console do navegador via header customizado.
 * Gerado pelo Copilot
 */
function logErroConsole($mensagem) {
    header("X-Transacao-Erro: " . rawurlencode($mensagem));
}

/**
 * Função para cadastrar uma nova transação com tratamento de erros detalhado.
 * Gerado pelo Copilot
 */
function cadastrarTransacao($id_usuario, $titulo, $descricao, $valor, $formaPagamento, $data, $tipo, $status, $idCategoria, $idContaRemetente, $idContaDestinataria = null) {
    global $conn;

    // Validação básica dos parâmetros
    if (empty($titulo) || empty($valor) || empty($data) || empty($tipo) || empty($status) || empty($idContaRemetente)) {
        $msg = "Parâmetros obrigatórios ausentes no cadastro de transação.";
        logErroConsole($msg);
        erro($msg);
        return false;
    }

    // Converte os valores para inteiros
    $idContaRemetente = intval($idContaRemetente);
    $idContaDestinataria = !empty($idContaDestinataria) ? intval($idContaDestinataria) : null;

    // Verifica se as contas são iguais em caso de transferência
    if ($tipo === 'Transferência' && $idContaRemetente === $idContaDestinataria) {
        $msg = 'Conta remetente e destinatária não podem ser iguais.';
        logErroConsole($msg);
        erro($msg);
        return false;
    }

    // Verifica se a conta remetente existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM CONTA WHERE ID_Conta = ?");
    if (!$stmt) {
        $msg = "Erro ao preparar verificação de conta remetente: " . $conn->error;
        logErroConsole($msg);
        erro($msg);
        return false;
    }
    $stmt->bind_param("i", $idContaRemetente);
    $stmt->execute();
    $stmt->bind_result($contaRemetenteExiste);
    $stmt->fetch();
    $stmt->close();

    if ($contaRemetenteExiste === 0) {
        $msg = "Conta remetente inválida. ID = " . htmlspecialchars($idContaRemetente);
        logErroConsole($msg);
        erro($msg);
        return false;
    }

    // Validação específica para transferência
    if ($tipo === 'Transferência') {
        if (empty($idContaDestinataria)) {
            $msg = "Transferência exige conta destinatária!";
            logErroConsole($msg);
            erro($msg);
            return false;
        }
        // Em transferência, ignora forma de pagamento
        $formaPagamento = 'transferencia';
    }

    // Verifica saldo para despesas ou transferências
    if (($tipo === 'Despesa' || $tipo === 'Transferência')) {
        $saldoConta = obterSaldoConta($idContaRemetente);
        if ($saldoConta === null) {
            $msg = "Não foi possível obter o saldo da conta remetente.";
            logErroConsole($msg);
            erro($msg);
            return false;
        }
        if ($saldoConta < $valor) {
            $msg = "Saldo insuficiente na conta remetente.";
            logErroConsole($msg);
            erro($msg);
            return false;
        }
    }

    // Query SQL para inserir a transação
    $sql = "INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_ContaDestinataria, ID_Usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $msg = "Erro ao preparar a declaração SQL: " . $conn->error;
        logErroConsole($msg);
        erro($msg);
        return false;
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
        $msg = "Erro ao executar a declaração SQL: " . $stmt->error;
        logErroConsole($msg);
        erro($msg);
        return false;
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