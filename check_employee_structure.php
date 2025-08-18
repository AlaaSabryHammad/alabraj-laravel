<?php

$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query("PRAGMA table_info(employees)");
echo "بنية جدول employees:\n";
while ($column = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$column['name']} ({$column['type']})\n";
}
