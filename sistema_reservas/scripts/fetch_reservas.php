<?php
include('../config/db_connect.php');

header('Content-Type: application/json');

$sql = "SELECT r.id, r.data_reserva, r.hora_inicio, r.hora_fim, s.nome AS sala_nome 
        FROM reservas r 
        JOIN salas s ON r.sala_id = s.id";
$result = $conn->query($sql);

$reservas = [];
while ($row = $result->fetch_assoc()) {
    $reservas[] = [
        'id' => $row['id'],
        'title' => $row['sala_nome'] . " - " . $row['hora_inicio'] . " às " . $row['hora_fim'],
        'start' => $row['data_reserva'] . 'T' . $row['hora_inicio'],
        'end' => $row['data_reserva'] . 'T' . $row['hora_fim']
    ];
}
echo json_encode($reservas);
?>
