<?php
session_start();
require_once 'conexao.php';  // Conexão com o banco de dados
require_once 'header.php';    // Cabeçalho com a estilização

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? null;

    if ($acao === 'login') {
        $usuario = trim($_POST['usuario'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        // Validação de campos obrigatórios
        if (empty($usuario) || empty($senha)) {
            $_SESSION['error_message'] = 'Usuário e senha são obrigatórios!';
            header('Location: index.php');
            exit;
        }

        $sql = "SELECT ID_Usuario, Nome, Senha FROM USUARIO WHERE Nome = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verifica a senha com hash
                $hashed_password = hash('sha256', $senha);
                if ($hashed_password === $user['Senha']) {
                    // Sessão ativa com os dados do usuário
                    $_SESSION['logado'] = true;
                    $_SESSION['id_usuario'] = $user['ID_Usuario'];
                    $_SESSION['usuario'] = $user['Nome'];

                    header('Location: pages/dashboard.php');
                    exit;
                } else {
                    $_SESSION['error_message'] = 'Senha inválida!';
                    header('Location: index.php');
                    exit;
                }
            } else {
                $_SESSION['error_message'] = 'Usuário não encontrado!';
                header('Location: index.php');
                exit;
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = 'Erro ao preparar a consulta!';
            header('Location: index.php');
            exit;
        }
    }

    // Registro de usuário
    if ($acao === 'register') {
        $usuario = trim($_POST['usuario'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        // Validação de campos obrigatórios
        if (empty($usuario) || empty($email) || empty($senha)) {
            $_SESSION['error_message'] = 'Todos os campos são obrigatórios!';
            header('Location: index.php');
            exit;
        }

        // Verificar se o email já está cadastrado
        $sql = "SELECT ID_Usuario FROM USUARIO WHERE Email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $_SESSION['error_message'] = 'Este email já está cadastrado!';
                header('Location: index.php');
                exit;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = 'Erro ao verificar o email!';
            header('Location: index.php');
            exit;
        }

        // Criação do novo usuário
        $hashed_password = hash('sha256', $senha);
        $dataCadastro = date('Y-m-d');

        $sql = "INSERT INTO USUARIO (Nome, Email, Senha, DataCadastro) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $usuario, $email, $hashed_password, $dataCadastro);
            if ($stmt->execute()) {
                // Definindo a mensagem de sucesso para o toast
                $_SESSION['success_message'] = 'Usuário cadastrado com sucesso! Agora, faça login.';
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['error_message'] = 'Erro ao cadastrar usuário!';
                header('Location: index.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'Erro ao preparar a consulta!';
            header('Location: index.php');
            exit;
        }
    }

    $conn->close();
}
?>

<head>
    <link rel="stylesheet" href="login/login.css">  <!-- Link para o CSS geral -->
    <link rel="stylesheet" href="assets/css/toast.css">  <!-- Link para o CSS do Toast -->
</head>

<body>
    <!-- Toast Container fora do container de login -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="toast-container">
            <div class="toast-notification success">
                <div class="toast-header">
                    <span class="toast-title">Sucesso</span>
                    <button type="button" class="toast-close" onclick="this.parentElement.parentElement.style.display = 'none';">×</button>
                </div>
                <div class="toast-message">
                    <?php 
                        echo htmlspecialchars($_SESSION['success_message']);
                        unset($_SESSION['success_message']);
                    ?>
                </div>
                <div class="toast-progress">
                    <div class="toast-progress-bar"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="container__buttons">
            <div class="container__btn-highlight"></div>

            <button type="button" class="container__toggle-btn container__toggle-btn--login">log in</button>
            <button type="button" class="container__toggle-btn container__toggle-btn--register">register</button>
        </div>

        <div class="container__social-icons center">
            <!-- Social media icons -->
            <a href="#">...</a> <!-- Add Social media icons here -->
        </div>

        <!-- Login -->
        <form id="login" class="form" action="index.php" method="POST">
            <input type="hidden" name="acao" value="login">
            <input type="text" class="form__input" name="usuario" placeholder="Username" required>
            <input type="password" class="form__input" name="senha" placeholder="Password" required>
            <div class="form__options">
                <input type="checkbox" class="form__check-box" id="login">
                <label for="login" class="form__terms">Remember me</label>
            </div>
            <button type="submit" class="form__submit-btn">log in</button>
        </form>

        <!-- Register -->
        <form id="register" class="form" action="index.php" method="POST">
            <input type="hidden" name="acao" value="register">
            <input type="text" class="form__input" name="usuario" placeholder="Username" required>
            <input type="email" class="form__input" name="email" placeholder="Email" required>
            <input type="password" class="form__input" name="senha" placeholder="Password" required>
            <div class="form__options">
                <input type="checkbox" class="form__check-box" id="register">
                <label for="register" class="form__terms">
                    I agree to the <a href="#" class="form__terms-link">Terms and Services</a>
                </label>
            </div>
            <button type="submit" class="form__submit-btn">register</button>
        </form>
    </div>

    <script src="login/login.js"></script> <!-- Link para o JS -->
</body>
