<?php

$host = getenv("MYSQL_HOST");
$user = getenv("MYSQL_USER");
$password = getenv("MYSQL_PASSWORD");
$database = getenv("MYSQL_DATABASE");

$con = mysqli_connect($host,$user,$password,$database);

// Verificação da conexão
if ($con->connect_error) {
    echo "Erro na conexão: " . $con->connect_error;
    exit;
}

?>