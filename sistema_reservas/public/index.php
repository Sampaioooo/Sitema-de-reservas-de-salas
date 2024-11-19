<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Reservas</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo $_SESSION['user_nome']; ?>!</h1>
    <a href="../auth/logout.php">Sair</a>
    <p><a href="../views/salas.php">Gerenciar Salas</a></p>
    <p><a href="../views/reservas.php">Gerenciar Reservas</a></p>
</body>
</html>
