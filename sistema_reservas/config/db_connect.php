<?php
$host = 'localhost';
$username = 'root';
$password = 'nova_senha'; 
$database = 'sistema_reservas';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
