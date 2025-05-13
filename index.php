<?php
session_start();
require_once 'conexao.php';
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? null;

    if ($acao === 'login') {
        $usuario = trim($_POST['usuario'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

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

                // Validação da senha com hash
                $hashed_password = hash('sha256', $senha);
                if ($hashed_password === $user['Senha']) {
                    // Variáveis de sessão
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

    $conn->close();
}
?>

<head>
    <link rel="stylesheet" href="login/login.css">
</head>

<body>
    <div class="container">
        <div class="cardLogin flipped">
            <!-- Front Side (Cadastro) -->
            <div class="card-side front">
                <div class="left-side">
                    <h1>Bem vindo de volta!</h1>
                    <p>Para se manter conectado,<br>faça login com suas informações pessoais</p>
                    <button class="btn" id="signInBtn">ENTRAR</button>
                </div>
                <div class="right-side">
                    <h2>Criar uma conta</h2>
                    <div class="social-icons">
                        <div class="social-icon"><i class="bi bi-facebook"></i></div>
                        <div class="social-icon"><i class="bi bi-google"></i></div>
                        <div class="social-icon"><i class="bi bi-linkedin"></i></div>
                    </div>

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
                    <h1>Olá amigo!</h1>
                    <p>Digite seus dados pessoais<br>e inicie a jornada conosco</p>
                    <button class="btn" id="signUpBtn">REGISTRAR-SE</button>
                </div>
            </div>

        </div>
    </div>

    <script src="login/login.js"></script>
</body>
