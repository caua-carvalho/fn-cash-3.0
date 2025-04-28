<?php
session_start();
require_once 'conexao.php';
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            $_SESSION['logged_in'] = true;
            $_SESSION['id_usuario'] = $user['ID_Usuario'];
            $_SESSION['usuario'] = $user['Nome'];

            header('Location: pages/home.php');
            exit;
        } else {
            $error_message = 'Senha inválida.';
        }
    } else {
        $error_message = 'Usuário não encontrado.';
    }

    $stmt->close();
    $conn->close();
}
?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <form action="index.php" method="POST">
                            <div class="form-group">
                                <label for="usuario">Usuário:</label>
                                <input type="text" id="usuario" name="usuario" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
