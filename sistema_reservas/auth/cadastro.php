<?php
session_start();
include('../config/db_connect.php');

// Verifica se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    header('Location: ../public/index.php');
    exit();
}

// Processa o cadastro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); // Lembre-se de usar password_hash em produção

    // Verifica se o e-mail já está cadastrado
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        // Cria um novo usuário
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
        if ($conn->query($sql)) {
            $sucesso = "Cadastro realizado com sucesso! Agora você pode fazer login.";
        } else {
            $erro = "Erro ao cadastrar. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
</head>
<body>
    <h1>Cadastro de Usuário</h1>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <?php if (isset($sucesso)) echo "<p style='color:green;'>$sucesso</p>"; ?>
    <form action="cadastro.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        <label for="email">E-mail:</label>
        <input type="email" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>
        <button type="submit" name="cadastrar">Cadastrar</button>
    </form>
    <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
</body>
</html>
