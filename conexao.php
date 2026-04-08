<?php
$conn = new mysqli("localhost", "root", "", "conecta_solidaria");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>

