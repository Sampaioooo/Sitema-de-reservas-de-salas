<?php
include('../config/db_connect.php');
include('../includes/header.php');

// Criar nova sala
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegue os valores do formulário
    $nome_sala = mysqli_real_escape_string($conn, $_POST['nome_sala']);
    $capacidade = (int)$_POST['capacidade']; // Obtenha o valor da capacidade
    $localizacao = mysqli_real_escape_string($conn, $_POST['localizacao']);
    
    // Certifique-se de que a coluna "capacidade" está sendo incluída na consulta SQL
    $sql = "INSERT INTO salas (nome, capacidade, localizacao) VALUES ('$nome_sala', '$capacidade', '$localizacao')";
    
    // Verifique se a consulta foi bem-sucedida
    if ($conn->query($sql) === TRUE) {
        echo "Sala inserida com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
}

// Lógica para deletar a sala
if (isset($_GET['delete'])) {
    $id_sala = (int)$_GET['delete'];

    // Verifica se o ID da sala é válido
    if ($id_sala > 0) {
        $sql_delete = "DELETE FROM salas WHERE id = $id_sala";

        if ($conn->query($sql_delete) === TRUE) {
            echo "Sala excluída com sucesso!";
            header("Location: salas.php"); // Redireciona para a página de gerenciamento de salas
            exit();
        } else {
            echo "Erro ao excluir sala: " . $conn->error;
        }
    }
}

// Listar salas
$sql = "SELECT * FROM salas";
$result = $conn->query($sql);
?>

<h1>Gerenciar Salas</h1>
<form method="POST" action="salas.php">
    <label for="nome_sala">Nome da Sala:</label>
    <input type="text" name="nome_sala" placeholder="Nome da Sala" required><br><br>
    
    <label for="capacidade">Capacidade:</label>
    <input type="number" name="capacidade" placeholder="Capacidade" required><br><br>
    
    <label for="localizacao">Localização:</label>
    <input type="text" name="localizacao" placeholder="Localização" required><br><br>
    
    <button type="submit">Adicionar Sala</button>
</form>

<h2>Salas Existentes</h2>
<?php
// Exibindo as salas existentes com o botão de excluir
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "Nome da Sala: " . $row['nome'] . " | Localização: " . $row['localizacao'] . " | Capacidade: " . $row['capacidade'] . "<br>";
        // Adicionando botão para excluir a sala
        echo "<a href='salas.php?delete=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir?\");'>Excluir</a><br><br>";
        echo "</div>";
    }
} else {
    echo "Nenhuma sala encontrada.";
}
?>

<!-- Botão para voltar para a página inicial -->
<a href="http://localhost/sistema_reservas/public/">Voltar para a página inicial</a><br><br>

<!-- Botão para ir para a página de reservas -->
<a href="http://localhost/sistema_reservas/views/reservas.php">Ir para Reservas</a><br><br>

<?php include('../includes/footer.php'); ?>

