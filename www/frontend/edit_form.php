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
    <title>Редагувати Компанію</title>
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

    <h1>Редагувати Компанію</h1>
    <form action="../backend/edit_company.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $company['id']; ?>">
        <input type="hidden" name="existing_photo" value="<?php echo htmlspecialchars($company['photo']); ?>">

        <label>Назва:
            <input type="text" name="name" value="<?php echo htmlspecialchars($company['name']); ?>" required>
        </label>
        <label>Опис:
            <textarea name="description"><?php echo htmlspecialchars($company['description']); ?></textarea>
        </label>
        <label>Контакт:
            <input type="text" name="contact" value="<?php echo htmlspecialchars($company['contact']); ?>">
        </label>
        <label>Email:
            <input type="email" name="email" value="<?php echo htmlspecialchars($company['email']); ?>">
        </label>
        <label>Контактна Особа:
            <input type="text" name="contact_person" value="<?php echo htmlspecialchars($company['contact_person']); ?>">
        </label>
        <label>Номер Сертифікату:
            <input type="text" name="certificate_number" value="<?php echo htmlspecialchars($company['certificate_number']); ?>">
        </label>
        <label>ІПН:
            <input type="text" name="tax_id" value="<?php echo htmlspecialchars($company['tax_id']); ?>">
        </label>
        <label>Статус Сертифікату:
            <select name="certificate_status">
                <option value="active" <?php echo ($company['certificate_status'] === 'active') ? 'selected' : ''; ?>>Активний</option>
                <option value="inactive" <?php echo ($company['certificate_status'] === 'inactive') ? 'selected' : ''; ?>>Неактивний</option>
            </select>
        </label>
        <label>Фото:
            <input type="file" name="photo">
        </label>
        <?php if ($company['photo']): ?>
            <img src="../assets/uploads/<?php echo htmlspecialchars($company['photo']); ?>" alt="Фото компанії" width="100"><br>
        <?php endif; ?>
        <button type="submit">Оновити Компанію</button>
    </form>
    <a href="index.php" class="button">Назад до Списку</a>
</body>
</html>
