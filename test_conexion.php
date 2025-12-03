<?php
header('Content-Type: text/plain');

// Variables de Railway (debes agregarlas en Railway Variables)
$host = getenv('mysqlHOST');
$port = getenv('mysqlPORT');
$dbname = getenv('mysqlDATAABASE');
$user = 'root';
$pass = getenv('mysqlPASSWORD');

echo "Intentando conectar a:\n";
echo "Host: $host\n";
echo "Puerto: $port\n";
echo "BD: $dbname\n";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ ¡Conexión exitosa a Railway MySQL!\n";
    
    // Mostrar tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tablas en la base de datos:\n";
    print_r($tables);
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Verifica que:\n";
    echo "1. Las variables estén en Railway\n";
    echo "2. Tu IP esté en allowlist (si aplica)\n";
    echo "3. La BD exista\n";
}
?>