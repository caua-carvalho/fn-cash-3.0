<?php
require_once '../conexao.php';

/**
 * Obtém todos os orçamentos cadastrados
 * @return array Array com orçamentos
 */
function obterOrcamentos() {
    global $conn;

    $sql = "SELECT 
                o.*, 
                c.Nome AS NomeCategoria,
                DATE_FORMAT(o.Inicio, '%d-%m-%Y') AS InicioFormatado, 
                DATE_FORMAT(o.Fim, '%d-%m-%Y') AS FimFormatado,
                COALESCE(g.TotalGasto, 0) AS GastoAtual,
                (o.Valor - COALESCE(g.TotalGasto, 0)) AS SaldoDisponivel
            FROM ORCAMENTO o
            LEFT JOIN CATEGORIA c ON o.ID_Categoria = c.ID_Categoria
            LEFT JOIN (
                SELECT 
                    t.ID_Categoria,
                    SUM(CASE WHEN t.Tipo = 'Despesa' AND t.Status = 'Efetivada' THEN t.Valor ELSE 0 END) AS TotalGasto
                FROM TRANSACAO t
                GROUP BY t.ID_Categoria
            ) g ON g.ID_Categoria = o.ID_Categoria
            ORDER BY o.Ativo DESC, o.Inicio DESC";

    $result = $conn->query($sql);
    $orcamentos = array();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Usar o formato formatado para exibição
            $row['Inicio'] = $row['InicioFormatado'];
            $row['Fim'] = $row['FimFormatado'];
            unset($row['InicioFormatado']);
            unset($row['FimFormatado']);
            
            $orcamentos[] = $row;
        }
    }
    
    return $orcamentos;
}

/**
 * Obtém um orçamento específico pelo ID
 * @param int $id ID do orçamento
 * @return array|null Array com dados do orçamento ou null se não encontrado
 */
function obterOrcamentoPorId($id) {
    global $conn;
    
    $sql = "SELECT 
                o.*, 
                c.Nome AS NomeCategoria
            FROM ORCAMENTO o
            LEFT JOIN CATEGORIA c ON o.ID_Categoria = c.ID_Categoria
            WHERE o.ID_Orcamento = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Cadastra um novo orçamento
 * @param int $idUsuario ID do usuário
 * @param int $idCategoria ID da categoria
 * @param string $titulo Título do orçamento
 * @param float $valor Valor planejado
 * @param string $inicio Data inicial (formato Y-m-d)
 * @param string $fim Data final (formato Y-m-d)
 * @param bool $status Status (ativo/inativo)
 * @return bool True se cadastrou com sucesso, False caso contrário
 */
function cadastrarOrcamento($idUsuario, $idCategoria, $titulo, $valor, $inicio, $fim, $status, $descricao = '-') {
    global $conn;
    
    $sql = "INSERT INTO ORCAMENTO (ID_Usuario, ID_Categoria, Titulo, Valor, Inicio, Fim, Ativo, Descricao) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    
    // Converte para tipos corretos
    $valorFloat = floatval($valor);
    $statusInt = (bool)$status ? 1 : 0;
    
    $stmt->bind_param("iisdsssi", $idUsuario, $idCategoria, $titulo, $valorFloat, $inicio, $fim, $statusInt, $descricao);
    
    return $stmt->execute();
}

/**
 * Edita um orçamento existente
 * @param int $id ID do orçamento
 * @param int $idCategoria ID da categoria
 * @param string $titulo Título do orçamento
 * @param float $valor Valor planejado
 * @param string $inicio Data inicial (formato Y-m-d)
 * @param string $fim Data final (formato Y-m-d)
 * @param bool $status Status (ativo/inativo)
 * @return bool True se editou com sucesso, False caso contrário
 */
function editarOrcamento($id, $idCategoria, $titulo, $valor, $inicio, $fim, $status, $descricao = '-') {
    global $conn;
    
    $sql = "UPDATE ORCAMENTO SET 
                ID_Categoria = ?, 
                Titulo = ?, 
                Valor = ?, 
                Inicio = ?, 
                Fim = ?, 
                Ativo = ?,
                Descricao = ?
            WHERE ID_Orcamento = ?";
            
    $stmt = $conn->prepare($sql);
    
    // Converte para tipos corretos
    $valorFloat = floatval($valor);
    $statusInt = (bool)$status ? 1 : 0;
    
    $stmt->bind_param("isdssisi", $idCategoria, $titulo, $valorFloat, $inicio, $fim, $statusInt, $descricao, $id);
    
    return $stmt->execute();
}

/**
 * Exclui um orçamento
 * @param int $id ID do orçamento
 * @return bool True se excluiu com sucesso, False caso contrário
 */
function deletarOrcamento($id) {
    global $conn;
    
    $sql = "DELETE FROM ORCAMENTO WHERE ID_Orcamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    return $stmt->execute();
}

/**
 * Obtém todas as categorias disponíveis para orçamentos (apenas categorias de despesa)
 * @return array Array com categorias
 */
function obterCategorias() {
    global $conn;
    
    $sql = "SELECT ID_Categoria, Nome FROM CATEGORIA WHERE Tipo = 'Despesa' AND Ativa = 1 ORDER BY Nome";
    $result = $conn->query($sql);
    $categorias = array();
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }
    
    return $categorias;
}

/**
 * Verifica se um usuário tem permissão para acessar um orçamento específico
 * @param int $idOrcamento ID do orçamento
 * @param int $idUsuario ID do usuário
 * @return bool True se tem permissão, False caso contrário
 */
function verificarPermissaoOrcamento($idOrcamento, $idUsuario) {
    global $conn;
    
    $sql = "SELECT COUNT(*) AS total FROM ORCAMENTO WHERE ID_Orcamento = ? AND ID_Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idOrcamento, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    }
    
    return false;
}

/**
 * Calcula o total gasto em uma categoria específica
 * @param int $idCategoria ID da categoria
 * @param string $dataInicio Data inicial (formato Y-m-d)
 * @param string $dataFim Data final (formato Y-m-d)
 * @return float Total gasto na categoria
 */
function calcularGastoCategoria($idCategoria, $dataInicio, $dataFim) {
    global $conn;
    
    $sql = "SELECT SUM(Valor) AS total FROM TRANSACAO
            WHERE ID_Categoria = ? AND Tipo = 'Despesa' AND Status = 'Efetivada'
            AND Data BETWEEN ? AND ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idCategoria, $dataInicio, $dataFim);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return floatval($row['total'] ?? 0);
    }
    
    return 0;
}
?>