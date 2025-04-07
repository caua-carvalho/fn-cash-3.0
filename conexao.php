<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'fncash';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo '<script> alert("Conexão com o banco de dados estabelecida com sucesso!"); </script>';
?>