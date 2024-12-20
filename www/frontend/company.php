<?php
require_once '../config/database.php';
header('Content-Type: text/html; charset=utf-8');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = :id");
$stmt->execute(array(':id' => $id));
$company = $stmt->fetch();

if (!$company) {
    header("Location: index.php?message=Компанію не знайдено");
    exit();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($company['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <!-- Навігаційна панель -->
    <nav>
        <ul>
            <li><a href="index.php">Головна</a></li>
            <li><a href="notebook.php">Блокнот</a></li>
        </ul>
    </nav>

    <h1><?php echo htmlspecialchars($company['name']); ?></h1>
    <?php if ($company['photo']): ?>
        <img src="../assets/uploads/<?php echo htmlspecialchars($company['photo']); ?>" alt="Фото компанії" width="200">
    <?php endif; ?>
    <p><strong>Опис:</strong> <?php echo htmlspecialchars($company['description']); ?></p>
    <p><strong>Контакт:</strong> <?php echo htmlspecialchars($company['contact']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($company['email']); ?></p>
    <p><strong>Контактна Особа:</strong> <?php echo htmlspecialchars($company['contact_person']); ?></p>
    <p><strong>Номер Сертифікату:</strong> <?php echo htmlspecialchars($company['certificate_number']); ?></p>
    <p><strong>ІПН:</strong> <?php echo htmlspecialchars($company['tax_id']); ?></p>
    <p><strong>Статус Сертифікату:</strong> <?php echo htmlspecialchars($company['certificate_status']); ?></p>

    <h3>Поділитись:</h3>
    <div class="social-buttons">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://yourdomain.com/frontend/company.php?id=' . $company['id']); ?>" target="_blank">Facebook</a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://yourdomain.com/frontend/company.php?id=' . $company['id']); ?>&text=<?php echo urlencode($company['name']); ?>" target="_blank" class="twitter">Twitter</a>
    </div>

    <a href="index.php" class="button">Назад до Списку</a>
</body>
</html>
