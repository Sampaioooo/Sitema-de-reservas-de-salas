<?php
session_start();
include('../config/db_connect.php');

// Redireciona para a página principal se já estiver logado
if (isset($_SESSION['user_id'])) {
    header('Location: ../public/index.php');
    exit();
}

// Processa o login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); // Lembre-se de usar password_hash em produção

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        header('Location: ../public/index.php');
        exit();
    } else {
        $erro = "Credenciais inválidas.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <form action="login.php" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>
        <button type="submit" name="login">Entrar</button>
    </form>
    <p>Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
</body>
</html>
