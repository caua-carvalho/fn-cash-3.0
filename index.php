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
    <link rel="stylesheet" href="assets\css/toast.css">  <!-- Link para o CSS do Toast -->
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
        <div class="cardLogin flipped">
            <!-- Front Side (Cadastro) -->
            <div class="card-side front">
                <div class="left-side">
                    <h1>Bem-vindo de volta!</h1>
                    <p>Faça login com suas informações pessoais para continuar</p>
                    <button class="btn" id="signInBtn">ENTRAR</button>
                </div>
                <div class="right-side">
                    <h2>Criar uma conta</h2>
                    <div class="social-icons">
                        <div class="social-icon"><i class="bi bi-facebook"></i></div>
                        <div class="social-icon"><i class="bi bi-google"></i></div>
                        <div class="social-icon"><i class="bi bi-linkedin"></i></div>
                    </div>

                    <!-- Exibição de mensagens de erro -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert" role="alert">
                            <?php 
                                echo htmlspecialchars($_SESSION['error_message']);
                                unset($_SESSION['error_message']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php" method="post" class="login">
                        <input type="hidden" name="acao" value="register">

                        <div class="input-container">
                            <input type="text" id="cad-usuario" name="usuario" placeholder=" " required>
                            <label for="cad-usuario">Usuário</label>
                        </div>

                        <div class="input-container">
                            <input type="email" id="cad-email" name="email" placeholder=" " required>
                            <label for="cad-email">E-mail</label>
                        </div>

                        <div class="input-container">
                            <input type="password" id="cad-senha" name="senha" placeholder=" " required>
                            <label for="cad-senha">Senha</label>
                        </div>

                        <button class="signup-btn">REGISTRAR-SE</button>
                    </form>
                </div>
            </div>

            <!-- Back Side (Login) -->
            <div class="card-side back">
                <div class="right-side">
                    <h2>Faça login na sua conta</h2>

                    <div class="social-icons">
                        <div class="social-icon"><i class="bi bi-facebook"></i></div>
                        <div class="social-icon"><i class="bi bi-google"></i></div>
                        <div class="social-icon"><i class="bi bi-linkedin"></i></div>
                    </div>

                    <!-- Exibição de mensagens de erro -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert" role="alert">
                            <?php 
                                echo htmlspecialchars($_SESSION['error_message']);
                                unset($_SESSION['error_message']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php" method="post" class="login">
                        <input type="hidden" name="acao" value="login">

                        <div class="input-container">
                            <input type="text" id="usuario_login" name="usuario" placeholder=" " required>
                            <label for="usuario_login">Usuário</label>
                        </div>

                        <div class="input-container">
                            <input type="password" id="senha_login" name="senha" placeholder=" " required>
                            <label for="senha_login">Senha</label>
                        </div>

                        <div class="container-btn">
                            <button type="submit" class="signup-btn">ENTRAR</button>
                        </div>
                    </form>
                </div>

                <div class="left-side">
                    <h1>Olá, amigo!</h1>
                    <p>Digite seus dados pessoais e comece sua jornada financeira</p>
                    <button class="btn" id="signUpBtn">REGISTRAR-SE</button>
                </div>
            </div>

        </div>
    </div>

    <script src="login/login.js"></script>
</body>
