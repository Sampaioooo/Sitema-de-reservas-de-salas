<?php
include('../config/db_connect.php');
include('../includes/header.php');

// Criar reserva
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['criar_reserva'])) {
    $usuario = $_POST['usuario'];
    $sala_id = $_POST['sala_id'];
    $data_reserva = $_POST['data_reserva'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];

    // Verificar se já existe uma reserva no intervalo
    $sql = "SELECT * FROM reservas 
            WHERE sala_id = $sala_id AND data_reserva = '$data_reserva' 
            AND (
                ('$hora_inicio' BETWEEN hora_inicio AND hora_fim) OR 
                ('$hora_fim' BETWEEN hora_inicio AND hora_fim)
            )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Erro: Sala já reservada neste horário.";
    } else {
        $sql = "INSERT INTO reservas (usuario, sala_id, data_reserva, hora_inicio, hora_fim) 
                VALUES ('$usuario', $sala_id, '$data_reserva', '$hora_inicio', '$hora_fim')";
        if ($conn->query($sql) === TRUE) {
            echo "Reserva criada com sucesso!";
        } else {
            echo "Erro ao criar reserva: " . $conn->error;
        }
    }
}

// Cancelar reserva
if (isset($_GET['cancelar'])) {
    $id_reserva = (int)$_GET['cancelar'];

    // Verifica se o ID da reserva é válido
    if ($id_reserva > 0) {
        $sql_cancelar = "DELETE FROM reservas WHERE id = $id_reserva";

        if ($conn->query($sql_cancelar) === TRUE) {
            echo "Reserva cancelada com sucesso!";
        } else {
            echo "Erro ao cancelar reserva: " . $conn->error;
        }
    }
}

// Listar reservas
$sql = "SELECT r.id, r.usuario, r.data_reserva, r.hora_inicio, r.hora_fim, s.nome AS sala_nome 
        FROM reservas r JOIN salas s ON r.sala_id = s.id";
$result = $conn->query($sql);
?>

<h1>Gerenciar Reservas</h1>

<!-- Formulário para criar reserva -->
<form action="reservas.php" method="post">
    <label for="usuario">Usuário:</label>
    <input type="text" name="usuario" required>
    <label for="sala_id">Sala:</label>
    <select name="sala_id" required>
        <?php
        $sql_salas = "SELECT * FROM salas";
        $salas_result = $conn->query($sql_salas);
        while ($sala = $salas_result->fetch_assoc()) {
            echo "<option value='" . $sala['id'] . "'>" . $sala['nome'] . "</option>";
        }
        ?>
    </select>
    <label for="data_reserva">Data da Reserva:</label>
    <input type="date" name="data_reserva" required>
    <label for="hora_inicio">Hora Início:</label>
    <input type="time" name="hora_inicio" required>
    <label for="hora_fim">Hora Fim:</label>
    <input type="time" name="hora_fim" required>
    <button type="submit" name="criar_reserva">Criar Reserva</button>
</form>

<h2>Reservas Existentes</h2>
<?php
while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['sala_nome'] . " - " . $row['usuario'] . " (" . $row['data_reserva'] . " - " . $row['hora_inicio'] . " até " . $row['hora_fim'] . ")";
    echo " <a href='reservas.php?cancelar=" . $row['id'] . "'>Cancelar Reserva</a></p>";
}
?>

<!-- Botões de navegação -->
<a href="http://localhost/sistema_reservas/public/">Voltar para a página inicial</a><br><br>
<a href="http://localhost/sistema_reservas/views/salas.php">Ir para Gerenciamento de Salas</a><br><br>

<?php include('../includes/footer.php'); ?>
