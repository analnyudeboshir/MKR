<?php
header('Content-Type: text/html; charset=utf-8');
$host = 'localhost';
$db   = 'company_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Помилки у винятки
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Використовувати реальні підготовлені запити
);

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Обробка помилок з'єднання
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
