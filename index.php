<?php
require_once 'header.php';
require_once 'conexao.php';
echo "teste";
?>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Digite seu usuário" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
    </div>
</div>