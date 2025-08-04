<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=abraj_db', 'root', '');

    echo "=== Materials Table Structure After Migration ===\n";
    $stmt = $pdo->query('DESCRIBE materials');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (strpos($row['Field'], 'unit') !== false) {
            echo sprintf("%-20s | %-15s | %-5s | %-5s | %-10s | %s\n",
                $row['Field'],
                $row['Type'],
                $row['Null'],
                $row['Key'],
                $row['Default'] ?? 'NULL',
                $row['Extra']
            );
        }
    }

    echo "\n=== Test Material Data ===\n";
    $stmt = $pdo->query('SELECT id, name, unit_of_measure FROM materials LIMIT 3');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("ID: %s | Name: %s | Unit_of_measure: %s\n",
            $row['id'],
            $row['name'],
            $row['unit_of_measure']
        );
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
