
<?php

require_once 'header.php';
require_once 'sidebar.php';
require_once '../conexao.php';

// Função para obter dados do usuário logado
function obterUsuario($idUsuario, $conn) {
    $stmt = $conn->prepare("SELECT * FROM USUARIO WHERE ID_Usuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Função para atualizar dados do usuário
function atualizarUsuario($idUsuario, $nome, $email, $senha, $conn) {
    if ($senha) {
        $senhaHash = hash('sha256', $senha);
        $stmt = $conn->prepare("UPDATE USUARIO SET Nome = ?, Email = ?, Senha = ? WHERE ID_Usuario = ?");
        $stmt->bind_param("sssi", $nome, $email, $senhaHash, $idUsuario);
    } else {
        $stmt = $conn->prepare("UPDATE USUARIO SET Nome = ?, Email = ? WHERE ID_Usuario = ?");
        $stmt->bind_param("ssi", $nome, $email, $idUsuario);
    }
    return $stmt->execute();
}

// Função para excluir usuário
function excluirUsuario($idUsuario, $conn) {
    $stmt = $conn->prepare("DELETE FROM USUARIO WHERE ID_Usuario = ?");
    $stmt->bind_param("i", $idUsuario);
    return $stmt->execute();
}

// Processamento dos formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['id_usuario'];
    if (isset($_POST['acao'])) {
        if ($_POST['acao'] === 'editar_dados') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            if (!$nome || !$email) {
                $_SESSION['mensagem_erro'] = "Nome e e-mail são obrigatórios.";
            } else {
                if (atualizarUsuario($idUsuario, $nome, $email, $senha, $conn)) {
                    $_SESSION['mensagem_sucesso'] = "Perfil atualizado com sucesso!";
                } else {
                    $_SESSION['mensagem_erro'] = "Erro ao atualizar perfil. Verifique os dados e tente novamente.";
                }
            }
        }
        if ($_POST['acao'] === 'alterar_senha') {
            $senhaAtual = trim($_POST['senha_atual'] ?? '');
            $novaSenha = trim($_POST['nova_senha'] ?? '');

            if (!$senhaAtual || !$novaSenha) {
                $_SESSION['mensagem_erro'] = "Preencha todos os campos.";
            } else {
                // Verifica senha atual
                $usuario = obterUsuario($idUsuario, $conn);
                $senhaAtualHash = hash('sha256', $senhaAtual);
                if ($usuario && $usuario['Senha'] === $senhaAtualHash) {
                    // Senha atual correta, pode alterar
                    if (atualizarUsuario($idUsuario, null, null, $novaSenha, $conn)) {
                        $_SESSION['mensagem_sucesso'] = "Senha alterada com sucesso!";
                    } else {
                        $_SESSION['mensagem_erro'] = "Erro ao alterar senha.";
                    }
                } else {
                    $_SESSION['mensagem_erro'] = "Senha atual incorreta.";
                }
            }
        }
        if ($_POST['acao'] === 'excluir_conta_confirmada') {
            if (excluirUsuario($idUsuario, $conn)) {
                session_destroy();

            } else {
                $_SESSION['mensagem_erro'] = "Erro ao excluir conta.";
            }
        }
        if ($_POST['acao'] === 'alterar_tema') {
            $_SESSION['tema'] = ($_POST['tema'] === 'dark') ? 'dark-theme' : 'light-theme';
            $_SESSION['mensagem_sucesso'] = "Tema alterado!";
        }
    }
}

// Obter dados do usuário logado
$idUsuario = $_SESSION['id_usuario'];
$usuario = obterUsuario($idUsuario, $conn);

// Mensagens de feedback
if (isset($_SESSION['mensagem_sucesso'])) {
    echo "<div class='toast-notification success'>{$_SESSION['mensagem_sucesso']}</div>";
    unset($_SESSION['mensagem_sucesso']);
}
if (isset($_SESSION['mensagem_erro'])) {
    echo "<div class='toast-notification error'>{$_SESSION['mensagem_erro']}</div>";
    unset($_SESSION['mensagem_erro']);
}

// Tema atual
$temaAtual = $_SESSION['tema'] ?? (isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'dark-theme');
?>

<div class="content">
    <div class="perfil-container">
        <h2 class="text-2xl font-bold mb-4">Meu Perfil</h2>
        <div class="perfil-cards">
            <!-- Dados Pessoais -->
            <div class="card perfil-card" onclick="abrirModal('modalEditarDados')">
                <div class="flex items-center gap-2">
                    <i class="bi bi-person-circle text-xl"></i>
                    <div>
                        <div class="font-semibold">Dados Pessoais</div>
                        <div class="text-muted text-xs" id="nomeAtualExibicao">
                            <strong><?php echo htmlspecialchars($usuario['Nome'] ?? ''); ?></strong><br>
                            <?php echo htmlspecialchars($usuario['Email'] ?? ''); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Senha -->
            <div class="card perfil-card" onclick="abrirModal('modalAlterarSenha')">
                <div class="flex items-center gap-2">
                    <i class="bi bi-lock-fill text-xl"></i>
                    <div>
                        <div class="font-semibold">Senha</div>
                        <div class="text-muted text-xs">********</div>
                    </div>
                </div>
            </div>
            <!-- Tema -->
            <div class="card perfil-card" onclick="abrirModal('modalTema')">
                <div class="flex items-center gap-2">
                    <i class="bi bi-moon-stars-fill text-xl"></i>
                    <div>
                        <div class="font-semibold">Tema</div>
                        <div class="text-muted text-xs" id="temaAtualExibicao">
                            <?php echo $temaAtual === 'dark-theme' ? 'Escuro' : 'Claro'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Excluir Conta -->
            <div class="card perfil-card perfil-card-danger" onclick="abrirModal('modalConfirmarExcluirConta')">
                <div class="flex items-center gap-2">
                    <i class="bi bi-trash-fill text-xl text-danger"></i>
                    <div>
                        <div class="font-semibold text-danger">Excluir Conta</div>
                        <div class="text-muted text-xs">Remover permanentemente</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 text-muted" style="font-size: 0.9em;">
            <span>Data de cadastro: <?php echo !empty($usuario['DataCadastro']) ? date('d/m/Y', strtotime($usuario['DataCadastro'])) : '-'; ?></span>
            <?php if (!empty($usuario['UltimoAcesso'])): ?>
                <span class="ms-2">Último acesso: <?php echo date('d/m/Y H:i', strtotime($usuario['UltimoAcesso'])); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Editar Dados -->
<div id="modalEditarDados" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:340px;">
        <span class="close" onclick="fecharModal('modalEditarDados')">&times;</span>
        <h3 class="mb-2">Editar Dados</h3>
        <form method="POST" autocomplete="off">
            <input type="hidden" name="acao" value="editar_dados">
            <div class="form-group mb-2">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['Nome'] ?? ''); ?>" required>
            </div>
            <div class="form-group mb-2">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['Email'] ?? ''); ?>" required>
            </div>
            <div class="form-group mb-2">
                <label for="senha" class="form-label">Nova Senha <span class="text-muted">(opcional)</span></label>
                <input type="password" class="form-control" id="senha" name="senha" autocomplete="new-password">
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModal('modalEditarDados')">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Alterar Senha -->
<div id="modalAlterarSenha" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:340px;">
        <span class="close" onclick="fecharModal('modalAlterarSenha')">&times;</span>
        <h3 class="mb-2">Alterar Senha</h3>
        <form id="formAlterarSenha" method="POST" autocomplete="off" onsubmit="event.preventDefault(); abrirModal('modalConfirmarAlterarSenha');">
            <input type="hidden" name="acao" value="alterar_senha">
            <div class="form-group mb-2">
                <label for="senha_atual" class="form-label">Senha Atual</label>
                <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
            </div>
            <div class="form-group mb-2">
                <label for="nova_senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModal('modalAlterarSenha')">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm">Alterar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Confirmar Alteração de Senha -->
<div id="modalConfirmarAlterarSenha" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:340px;">
        <span class="close" onclick="fecharModal('modalConfirmarAlterarSenha')">&times;</span>
        <h3 class="mb-2">Confirmar Alteração</h3>
        <p style="font-size:0.95em;">Tem certeza que deseja alterar sua senha?</p>
        <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModal('modalConfirmarAlterarSenha')">Cancelar</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="confirmarAlterarSenha()">Confirmar</button>
        </div>
    </div>
</div>

<!-- Modal Tema -->
<div id="modalTema" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:340px;">
        <span class="close" onclick="fecharModal('modalTema')">&times;</span>
        <h3 class="mb-2">Alterar Tema</h3>
        <form id="formTema" method="POST" onsubmit="event.preventDefault(); salvarTema();">
            <input type="hidden" name="acao" value="alterar_tema">
            <div class="form-group mb-2">
                <label class="form-label">Escolha o tema:</label>
                <select name="tema" id="selectTema" class="form-control">
                    <option value="dark" <?php echo $temaAtual === 'dark-theme' ? 'selected' : ''; ?>>Escuro</option>
                    <option value="light" <?php echo $temaAtual === 'light-theme' ? 'selected' : ''; ?>>Claro</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModal('modalTema')">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Confirmar Exclusão -->
<div id="modalConfirmarExcluirConta" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:340px;">
        <span class="close" onclick="fecharModal('modalConfirmarExcluirConta')">&times;</span>
        <h3 class="mb-2 text-danger">Excluir Conta</h3>
        <p style="font-size:0.95em;">Tem certeza que deseja excluir sua conta? Esta ação não poderá ser desfeita.</p>
        <form id="formExcluirConta" method="POST" onsubmit="event.preventDefault(); abrirModal('modalConfirmarExcluirContaFinal');">
            <input type="hidden" name="acao" value="excluir_conta_confirmada">
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModal('modalConfirmarExcluirConta')">Cancelar</button>
                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Confirmar Exclusão Final -->
<div id="modalConfirmarExcluirContaFinal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:340px;">
        <span class="close" onclick="fecharModal('modalConfirmarExcluirContaFinal')">&times;</span>
        <h3 class="mb-2 text-danger">Confirmação Final</h3>
        <p style="font-size:0.95em;">Esta ação é irreversível. Deseja realmente excluir sua conta?</p>
        <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModal('modalConfirmarExcluirContaFinal')">Cancelar</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarExcluirConta()">Confirmar</button>
        </div>
    </div>
</div>

<style>
.perfil-container {
    max-width: 400px;
    margin-left: 0;
    margin-right: auto;
    padding-left: 1rem;
}
.perfil-cards {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.perfil-card {
    border-radius: 8px;
    background: var(--color-surface, #fff);
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    cursor: pointer;
    transition: box-shadow 0.2s;
    padding: 12px 16px;
}
.perfil-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    background: var(--color-surface-hover, #f7f7f7);
}
.perfil-card-danger {
    border: 1px solid #dc3545;
}
.modal { position: fixed; z-index: 9999; left: 0; top: 0; width: 100vw; height: 100vh; overflow: auto; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; }
.modal-content { background: var(--color-surface, #fff); padding: 1.5rem; border-radius: 8px; min-width: 260px; max-width: 95vw; position: relative; }
.close { position: absolute; right: 1rem; top: 1rem; font-size: 1.3rem; cursor: pointer; }
</style>

<script>
function abrirModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
}
// Toast notification auto-hide
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        document.querySelectorAll('.toast-notification').forEach(function (el) {
            el.classList.add('hiding');
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);

    // Aplica o tema salvo na sessão/cookie ao carregar
    let tema = '<?php echo $temaAtual; ?>';
    document.body.classList.remove('dark-theme', 'light-theme');
    document.body.classList.add(tema);
});

// Confirmação de alteração de senha
function confirmarAlterarSenha() {
    fecharModal('modalConfirmarAlterarSenha');
    document.getElementById('formAlterarSenha').submit();
}

// Confirmação de exclusão de conta
function confirmarExcluirConta() {
    fecharModal('modalConfirmarExcluirContaFinal');
    document.getElementById('formExcluirConta').submit();
}
function salvarTema() {
    var select = document.getElementById('selectTema');
    var tema = select.value === 'dark' ? 'dark-theme' : 'light-theme';
    document.body.classList.remove('dark-theme', 'light-theme');
    document.body.classList.add(tema);

    // Atualiza texto do card
    document.getElementById('temaAtualExibicao').innerText = tema === 'dark-theme' ? 'Escuro' : 'Claro';

    // Salva no localStorage e no cookie
    localStorage.setItem('theme', tema);
    document.cookie = 'theme=' + tema + ';path=/;max-age=31536000';

    // Envia para o backend
    var form = document.getElementById('formTema');
    var formData = new FormData(form);
    fetch('', {
        method: 'POST',
        body: formData
    }).then(() => {
        fecharModal('modalTema');
    });
}
</script>

<?php require_once 'footer.php'; ?>