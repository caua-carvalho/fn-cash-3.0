<?php
session_start();
require_once 'conexao.php';
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? null;

    if ($acao == 'login') {
        $usuario = $_POST['usuario'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $sql = "SELECT ID_Usuario, Nome, Senha FROM USUARIO WHERE Nome = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // VALIDACAO DA SENHA COM HASH
            $hashed_password = hash('sha256', $senha);
            if ($hashed_password === $user['Senha']) {
                // VARIAVEIS DE SESSAO

                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $user['ID_Usuario'];
                $_SESSION['usuario'] = $user['Nome'];

                header('Location: pages/home.php');
                exit;
            } 
            
            else {
                $error_message = 'Senha inválida.';
            }
        } 
        
        else {
            $error_message = 'Usuário não encontrado.';
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<heade>
    <link rel="stylesheet" href="login/login.css">
</heade>


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
                    <div class="divider">ou use seu e -mail para registro</div>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Name">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Email">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Password">
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

                    <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                    <?php endif; ?>

                    <div class="divider">ou use sua conta de e -mail</div>

                    <form action="index.php" method="post" class="login">
                        <input type="hidden" name="acao" value="login">

                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="text" id="usuario" name="usuario" class="form-control" required>
                        </div>

                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>

                        <div class="divider">Esqueceu sua senha?</div>
                        <button class="signup-btn">ENTRAR</button>
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