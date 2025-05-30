<?php
// Gerado pelo Copilot
header('Content-Type: application/json'); // Força resposta JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'funcoes.php';
    
    try {
        // Debug dos dados recebidos
        error_log('POST recebido: ' . print_r($_POST, true));
        
        if ($_POST['acao'] === 'cadastrarTransacao') {
            // Força valores padrão se não existirem
            $formaPagamento = $_POST['tipoTransacao'] === 'Transferência' ? 'transferencia' : $_POST['formaPagamento'];
            $categoriaId = isset($_POST['categoriaTransacao']) ? $_POST['categoriaTransacao'] : null;
            $contaDestinatariaId = isset($_POST['contaDestinataria']) ? $_POST['contaDestinataria'] : null;

            $resultado = cadastrarTransacao(
                1, // ID_Usuario fixo para teste
                $_POST['tituloTransacao'],
                $_POST['descricaoTransacao'],
                $_POST['valorTransacao'],
                $formaPagamento,
                $_POST['dataTransacao'],
                $_POST['tipoTransacao'],
                $_POST['statusTransacao'],
                $categoriaId,
                $_POST['contaRemetente'],
                $contaDestinatariaId
            );
            
            if (!$resultado) {
                throw new Exception("Falha ao cadastrar transação");
            }

            echo json_encode(['success' => true]);
            exit;
        }
    } catch (Exception $e) {
        error_log('Erro no cadastro: ' . $e->getMessage());
        http_response_code(400);
        header('X-Transacao-Erro: ' . rawurlencode($e->getMessage()));
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}
