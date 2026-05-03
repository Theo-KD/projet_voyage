<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "voyage";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>