<?php

$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query('SELECT name FROM sqlite_master WHERE type="table" ORDER BY name');
echo "الجداول الموجودة:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$row['name']}\n";
}
