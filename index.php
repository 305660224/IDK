<?php
// Usa las variables de Railway directamente
$host = getenv('mysqlHOST');
$port = getenv('mysqlPORT');
$database = getenv('mysqlDATAABASE');
$username = 'root'; // o el usuario que tengas
$password = getenv('mysqlPASSWORD');

// Conexión
$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "¡Conectado a Railway MySQL exitosamente!";
?>