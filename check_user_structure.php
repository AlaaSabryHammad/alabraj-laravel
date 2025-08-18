<?php

$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query("PRAGMA table_info(users)");
echo "بنية جدول users:\n";
while ($column = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- {$column['name']} ({$column['type']})\n";
}
