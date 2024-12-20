<?php
require_once '../config/database.php';
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Спочатку отримуємо назву фото для видалення з файлової системи
    $stmt = $pdo->prepare("SELECT photo FROM companies WHERE id = :id");
    $stmt->execute(array(':id' => $id));
    $company = $stmt->fetch();

    if ($company && $company['photo']) {
        $photoPath = '../assets/uploads/' . $company['photo'];
        if (file_exists($photoPath)) {
            unlink($photoPath); // Видаляємо файл
        }
    }

    // Видалення запису з бази
    $stmt = $pdo->prepare("DELETE FROM companies WHERE id = :id");
    $stmt->execute(array(':id' => $id));

    //Перехід на іншу сторінку
    header("Location: ../frontend/index.php?message=Company deleted successfully");
    exit();
}
?>
