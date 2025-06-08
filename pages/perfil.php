<?php
require_once 'header.php';
require_once 'sidebar.php';
require_once '../conexao.php';

// Função para obter usuário - Gerado pelo Copilot
function obterUsuario($idUsuario, $conn) {
    $stmt = $conn->prepare("SELECT * FROM USUARIO WHERE ID_Usuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Função para atualizar usuário - Gerado pelo Copilot
function atualizarUsuario($idUsuario, $nome, $email, $conn) {
    $stmt = $conn->prepare("UPDATE USUARIO SET Nome = ?, Email = ? WHERE ID_Usuario = ?");
    $stmt->bind_param("ssi", $nome, $email, $idUsuario);
    return $stmt->execute();
}

// Função para atualizar senha - Gerado pelo Copilot
function atualizarSenhaUsuario($idUsuario, $novaSenha, $conn) {
    $senhaHash = hash('sha256', $novaSenha);
    $stmt = $conn->prepare("UPDATE USUARIO SET Senha = ? WHERE ID_Usuario = ?");
    $stmt->bind_param("si", $senhaHash, $idUsuario);
    return $stmt->execute();
}

// Função para excluir usuário - Gerado pelo Copilot
function excluirUsuario($idUsuario, $conn) {
    $stmt = $conn->prepare("DELETE FROM USUARIO WHERE ID_Usuario = ?");
    $stmt->bind_param("i", $idUsuario);
    return $stmt->execute();
}

// Gerado pelo Copilot - Função para pegar a aba ativa do POST ou manter padrão
function getAbaAtiva() {
    if (isset($_POST['aba_ativa'])) return $_POST['aba_ativa'];
    return 'aba-dados';
}

// Processamento dos formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['id_usuario'];
    $abaAtiva = getAbaAtiva();
    if (isset($_POST['acao'])) {
        if ($_POST['acao'] === 'editar_dados') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            if (!$nome || !$email) {
                $_SESSION['mensagem_erro'] = "Nome e e-mail são obrigatórios.";
            } else {
                if (atualizarUsuario($idUsuario, $nome, $email, $conn)) {
                    $_SESSION['mensagem_sucesso'] = "Dados atualizados!";
                } else {
                    $_SESSION['mensagem_erro'] = "Erro ao atualizar dados.";
                }
            }
        }
        if ($_POST['acao'] === 'alterar_senha') {
            if (!isset($_POST['confirmar_alteracao_senha']) || $_POST['confirmar_alteracao_senha'] !== '1') {
                $_SESSION['mensagem_erro'] = "Confirme a alteração da senha.";
            } else {
                $senhaAtual = trim($_POST['senha_atual'] ?? '');
                $novaSenha = trim($_POST['nova_senha'] ?? '');
                if (!$senhaAtual || !$novaSenha) {
                    $_SESSION['mensagem_erro'] = "Preencha todos os campos.";
                } else {
                    $usuario = obterUsuario($idUsuario, $conn);
                    $senhaAtualHash = hash('sha256', $senhaAtual);
                    if ($usuario && $usuario['Senha'] === $senhaAtualHash) {
                        if (atualizarSenhaUsuario($idUsuario, $novaSenha, $conn)) {
                            $_SESSION['mensagem_sucesso'] = "Senha alterada com sucesso!";
                        } else {
                            $_SESSION['mensagem_erro'] = "Erro ao alterar senha.";
                        }
                    } else {
                        $_SESSION['mensagem_erro'] = "Senha atual incorreta.";
                    }
                }
            }
        }
        if ($_POST['acao'] === 'excluir_conta_confirmada') {
            if (excluirUsuario($idUsuario, $conn)) {
                session_destroy();
                header("Location: ../login.php");
                exit;
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao excluir conta.";
            }
        }
        if ($_POST['acao'] === 'alterar_tema') {
            $_SESSION['tema'] = ($_POST['tema'] === 'dark') ? 'dark-theme' : 'light-theme';
            $_SESSION['mensagem_sucesso'] = "Tema alterado!";
        }
        if ($_POST['acao'] === 'alterar_idioma') {
            $_SESSION['idioma'] = $_POST['idioma'];
            $_SESSION['mensagem_sucesso'] = "Idioma alterado!";
        }
        if ($_POST['acao'] === 'notificacoes') {
            $_SESSION['mensagem_sucesso'] = "Preferências de notificação salvas!";
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

// Tema e idioma atuais
$temaAtual = $_SESSION['tema'] ?? (isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'dark-theme');
$idiomaAtual = $_SESSION['idioma'] ?? 'pt';
$abaAtiva = $_POST['aba_ativa'] ?? 'aba-dados';
?>

<div class="page-title mb-4">Configurações</div>
<div class="config-tabs-bar">
    <button class="config-tab-btn" id="btn-aba-dados" onclick="abrirAba('aba-dados')">Dados Pessoais</button>
    <button class="config-tab-btn" id="btn-aba-preferencias" onclick="abrirAba('aba-preferencias')">Preferências</button>
    <button class="config-tab-btn" id="btn-aba-notificacoes" onclick="abrirAba('aba-notificacoes')">Notificações</button>
    <button class="config-tab-btn" id="btn-aba-seguranca" onclick="abrirAba('aba-seguranca')">Segurança</button>
</div>
<div class="config-tabs-content">
    <!-- Dados Pessoais -->
    <div id="aba-dados" class="config-tab-content">
        <div class="form-group">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control-underline" id="nome" value="<?php echo htmlspecialchars($usuario['Nome'] ?? ''); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control-underline" id="email" value="<?php echo htmlspecialchars($usuario['Email'] ?? ''); ?>" readonly>
        </div>
        <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalEditarInfo()">Alterar Informações</button>
        </div>
        <div class="mt-3 text-muted" style="font-size: 0.95em;">
            <span>Data de cadastro: <?php echo !empty($usuario['DataCadastro']) ? date('d/m/Y', strtotime($usuario['DataCadastro'])) : '-'; ?></span>
            <?php if (!empty($usuario['UltimoAcesso'])): ?>
                <span class="ms-2">Último acesso: <?php echo date('d/m/Y H:i', strtotime($usuario['UltimoAcesso'])); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <!-- Modal para editar informações - AGORA PADRONIZADO -->
    <div id="modalEditarInfo" class="modal" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px; width:100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Informações</h5>
                    <button type="button" class="close" onclick="fecharModalEditarInfo()" aria-label="Fechar">
                        &times;
                    </button>
                </div>
                <form method="POST" autocomplete="off" class="config-form" style="margin-bottom:0;">
                    <input type="hidden" name="acao" value="editar_dados">
                    <input type="hidden" name="aba_ativa" id="inputAbaAtivaModal" value="aba-dados">
                    <div class="modal-body">
                        <div class="form-group">
                        <label for="modal_nome" class="form-label">Nome</label>
                        <input type="text" class="form-control-underline" id="modal_nome" name="nome" value="<?php echo htmlspecialchars($usuario['Nome'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                        <label for="modal_email" class="form-label">E-mail</label>
                        <input type="email" class="form-control-underline" id="modal_email" name="email" value="<?php echo htmlspecialchars($usuario['Email'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer flex justify-end gap-2 mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModalEditarInfo()">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Preferências -->
    <div id="aba-preferencias" class="config-tab-content">
        <form method="POST" class="config-form" style="margin-bottom:1.5rem;">
            <input type="hidden" name="acao" value="alterar_tema">
            <input type="hidden" name="aba_ativa" id="inputAbaAtivaTema" value="aba-preferencias">
            <div class="form-group">
                <label class="form-label">Tema</label>
                <select name="tema" id="selectTema" class="form-control-underline" onchange="salvarTema()">
                    <option value="dark" <?php echo $temaAtual === 'dark-theme' ? 'selected' : ''; ?>>Escuro</option>
                    <option value="light" <?php echo $temaAtual === 'light-theme' ? 'selected' : ''; ?>>Claro</option>
                </select>
            </div>
        </form>
        <form method="POST" class="config-form">
            <input type="hidden" name="acao" value="alterar_idioma">
            <input type="hidden" name="aba_ativa" id="inputAbaAtivaIdioma" value="aba-preferencias">
            <div class="form-group">
                <label class="form-label">Idioma</label>
                <select name="idioma" class="form-control-underline" onchange="this.form.submit()">
                    <option value="pt" <?php echo $idiomaAtual === 'pt' ? 'selected' : ''; ?>>Português</option>
                    <option value="en" <?php echo $idiomaAtual === 'en' ? 'selected' : ''; ?>>Inglês</option>
                </select>
            </div>
        </form>
    </div>
    <!-- Notificações -->
    <div id="aba-notificacoes" class="config-tab-content">
        <form method="POST" class="config-form">
            <input type="hidden" name="acao" value="notificacoes">
            <input type="hidden" name="aba_ativa" id="inputAbaAtivaNotificacoes" value="aba-notificacoes">
            <div class="form-group">
                <label class="form-label">Receber notificações por e-mail?</label>
                <select name="notifica_email" class="form-control-underline">
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Receber resumo mensal?</label>
                <select name="notifica_resumo" class="form-control-underline">
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
            </div>
        </form>
    </div>
    <!-- Segurança -->
    <div id="aba-seguranca" class="config-tab-content">
        <form id="formAlterarSenha" method="POST" class="config-form" style="margin-bottom:1.5rem;" onsubmit="return confirmarAlteracaoSenha(event)">
            <input type="hidden" name="acao" value="alterar_senha">
            <input type="hidden" name="aba_ativa" id="inputAbaAtivaSeguranca" value="aba-seguranca">
            <input type="hidden" name="confirmar_alteracao_senha" id="inputConfirmarAlteracaoSenha" value="0">
            <div class="form-group">
                <label for="senha_atual" class="form-label">Senha Atual</label>
                <input type="password" class="form-control-underline" id="senha_atual" name="senha_atual" required>
            </div>
            <div class="form-group">
                <label for="nova_senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control-underline" id="nova_senha" name="nova_senha" required>
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-sm">Alterar Senha</button>
            </div>
        </form>
        <!-- Modal de confirmação de alteração de senha -->
        <div id="modalConfirmarSenha" class="modal" style="display:none;">
            <div class="modal-dialog" style="max-width:380px; width:100%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Alteração</h5>
                        <button type="button" class="close" onclick="fecharModalConfirmarSenha()" aria-label="Fechar">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        Tem certeza que deseja alterar sua senha?
                    </div>
                    <div class="modal-footer flex justify-end gap-2 mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="fecharModalConfirmarSenha()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="confirmarSubmitAlterarSenha()">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" class="config-form">
            <input type="hidden" name="acao" value="excluir_conta_confirmada">
            <div class="alert alert-danger mb-2" style="font-size:0.95em;">
                <strong>Atenção:</strong> Esta ação é irreversível. Sua conta será excluída permanentemente.
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="submit" class="btn btn-danger btn-sm">Excluir Conta</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Gerado pelo Copilot */
.page-title {
    font-size: 2rem;
    font-weight: bold;
    margin-top: 2rem;
    margin-left: 0;
    color: var(--color-text, #222);
    letter-spacing: -1px;
}
.config-tabs-bar {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    margin-left: 0;
    border-bottom: 1.5px solid var(--color-border, #e0e0e0);
}
.config-tab-btn {
    background: none;
    border: none;
    border-bottom: 2.5px solid transparent;
    border-radius: 0;
    padding: 0.7rem 1.3rem 0.5rem 1.3rem;
    font-weight: 500;
    font-size: 1.08rem;
    color: var(--color-text, #222);
    cursor: pointer;
    transition: border-color 0.2s, color 0.2s;
    margin-bottom: -2px;
}
.config-tab-btn.active, .config-tab-btn:focus {
    border-bottom: 2.5px solid var(--color-primary, #07a362);
    color: var(--color-primary, #07a362);
    background: none;
}
.config-tabs-content {
    margin-top: 0.5rem;
    max-width: 420px;
    margin-left: 0;
}
.config-tab-content {
    display: none;
    animation: fadeIn 0.3s;
}
.config-tab-content.active {
    display: block;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px);}
    to { opacity: 1; transform: translateY(0);}
}
.config-form .form-group {
    margin-bottom: 1.3rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.config-form .form-label {
    font-weight: 500;
    margin-bottom: 0.2rem;
    color: var(--color-text, #222);
}
.form-control-underline {
    border: none;
    border-bottom: 2px solid var(--color-border, #e0e0e0);
    border-radius: 0;
    background: transparent;
    font-size: 1.07rem;
    padding: 0.45rem 0.1rem 0.35rem 0.1rem;
    width: 100%;
    color: var(--color-text, #222);
    transition: border-color 0.2s;
    outline: none;
    box-shadow: none;
}
.form-control-underline:focus {
    border-bottom: 2px solid var(--color-primary, #07a362);
    background: transparent;
}
.config-form .btn {
    margin-top: 0.2rem;
}
.mt-3 { margin-top: 1rem; }
.text-muted { color: #888; }
.flex { display: flex; }
.justify-end { justify-content: flex-end; }
.gap-2 { gap: 0.5rem; }
@media (max-width: 600px) {
    .config-tabs-content { max-width: 100%; }
    .page-title { font-size: 1.3rem; margin-top: 1.2rem; }
    .config-tab-btn { font-size: 0.98rem; padding: 0.6rem 0.7rem 0.4rem 0.7rem; }
}
/* Modal padrão do sistema - Gerado pelo Copilot */
.modal {
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-dialog {
    background: transparent;
    border-radius: 8px;
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: var(--color-surface, #222);
    color: var(--color-text, #fff);
    border-radius: 8px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    width: 100%;
    pointer-events: auto;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.modal-header {
    background: linear-gradient(135deg, var(--color-primary, #07a362), var(--color-primary-700, #053f27));
    color: #fff;
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid var(--color-border, #444);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}
.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
    width: 100%;
    text-align: center;
    font-weight: 600;
    color: #fff;
}
.modal-header .close {
    padding: 0;
    margin: 0;
    background-color: transparent;
    border: 0;
    color: #fff;
    font-size: 1.5rem;
    line-height: 1;
    opacity: 0.7;
    position: absolute;
    right: 1.5rem;
    top: 1.2rem;
    transition: all 0.2s;
}
.modal-header .close:hover {
    opacity: 1;
    transform: rotate(90deg);
}
.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 2rem 1.5rem;
    background: var(--color-surface, #222);
    color: var(--color-text, #fff);
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--color-border, #444);
    gap: 0.5rem;
    background: var(--color-surface, #222);
}
@media (max-width: 600px) {
    .modal-dialog { max-width: 98vw; }
    .modal-body, .modal-header, .modal-footer { padding-left: 1rem; padding-right: 1rem; }
}
</style>

<script>
// Gerado pelo Copilot
function abrirAba(id) {
    document.querySelectorAll('.config-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.config-tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.getElementById('btn-' + id).classList.add('active');
    localStorage.setItem('abaAtivaPerfil', id);
    document.querySelectorAll('input[name="aba_ativa"]').forEach(function(input) {
        input.value = id;
    });
}
function abrirModalEditarInfo() {
    document.getElementById('modalEditarInfo').style.display = 'flex';
    setTimeout(function() {
        var nomeInput = document.getElementById('modal_nome');
        if (nomeInput) nomeInput.focus();
    }, 100);
    document.getElementById('inputAbaAtivaModal').value = localStorage.getItem('abaAtivaPerfil') || 'aba-dados';
}
function fecharModalEditarInfo() {
    document.getElementById('modalEditarInfo').style.display = 'none';
}
function confirmarAlteracaoSenha(event) {
    event.preventDefault();
    document.getElementById('modalConfirmarSenha').style.display = 'flex';
    return false;
}
function fecharModalConfirmarSenha() {
    document.getElementById('modalConfirmarSenha').style.display = 'none';
}
function confirmarSubmitAlterarSenha() {
    document.getElementById('inputConfirmarAlteracaoSenha').value = '1';
    fecharModalConfirmarSenha();
    document.getElementById('formAlterarSenha').submit();
}
function salvarTema() {
    var select = document.getElementById('selectTema');
    var tema = select.value === 'dark' ? 'dark-theme' : 'light-theme';
    document.body.classList.remove('dark-theme', 'light-theme');
    document.body.classList.add(tema);
    localStorage.setItem('theme', tema);
    document.cookie = 'theme=' + tema + ';path=/;max-age=31536000';
    select.form.submit();
}
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        document.querySelectorAll('.toast-notification').forEach(function (el) {
            el.classList.add('hiding');
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
    let tema = '<?php echo $temaAtual; ?>';
    document.body.classList.remove('dark-theme', 'light-theme');
    document.body.classList.add(tema);
    let abaAtiva = localStorage.getItem('abaAtivaPerfil') || '<?php echo $abaAtiva; ?>';
    abrirAba(abaAtiva);
});
</script>

<?php require_once 'footer.php'; ?>