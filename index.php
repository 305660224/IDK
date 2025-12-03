<?php
// Solo para desarrollo - NUNCA en producción sin autenticación
header('Content-Type: text/html; charset=utf-8');

// Conexión INTERNA (funciona dentro de Railway)
$host = getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$port = getenv('MYSQLPORT') ?: 3306;
$dbname = getenv('MYSQLDATABASE') ?: 'railway';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD');

echo "<h1>🔍 Vista de Base de Datos Railway</h1>";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Mostrar todas las tablas
    echo "<h2>📊 Tablas en la base de datos</h2>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p>No hay tablas en la base de datos.</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li><a href='#table-$table'>$table</a></li>";
        }
        echo "</ul>";
        
        // 2. Mostrar contenido de cada tabla
        foreach ($tables as $table) {
            echo "<hr>";
            echo "<h3 id='table-$table'>Tabla: $table</h3>";
            
            // Mostrar estructura
            echo "<h4>Estructura:</h4>";
            $structure = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            foreach ($structure as $column) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
                echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
                echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
                echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
                echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Mostrar datos (limitado a 50 filas)
            echo "<h4>Datos (primeras 50 filas):</h4>";
            $data = $pdo->query("SELECT * FROM `$table` LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($data)) {
                echo "<p>La tabla está vacía.</p>";
            } else {
                echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
                // Encabezados
                echo "<tr>";
                foreach (array_keys($data[0]) as $header) {
                    echo "<th>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr>";
                
                // Datos
                foreach ($data as $row) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>" . htmlspecialchars($cell ?? 'NULL') . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>