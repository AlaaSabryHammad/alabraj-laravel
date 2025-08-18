<?php

$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query('SELECT migration FROM migrations ORDER BY id DESC LIMIT 10');
echo "آخر 10 migrations:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$row['migration']}\n";
}
