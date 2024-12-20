<?php
session_start();
require_once '../config/database.php';
header('Content-Type: text/html; charset=utf-8');
// Ініціалізація блокноту в сесії
if (!isset($_SESSION['notebook'])) {
    $_SESSION['notebook'] = array();
}

// Додавання до блокноту
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Забезпечення, що ID є цілим числом
    if (!in_array($id, $_SESSION['notebook'])) {
        $_SESSION['notebook'][] = $id;
        $message = "Компанію додано до блокноту";
    } else {
        $message = "Компанія вже додана до блокноту";
    }
    header("Location: index.php?message=" . urlencode($message));
    exit();
}

// Видалення з блокноту
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (($key = array_search($id, $_SESSION['notebook'])) !== false) {
        unset($_SESSION['notebook'][$key]);
        $_SESSION['notebook'] = array_values($_SESSION['notebook']); // Перегрупувати індекси
        $message = "Компанію видалено з блокноту";
    } else {
        $message = "Компанія не знайдена у блокноті";
    }
    header("Location: notebook.php?message=" . urlencode($message));
    exit();
}

// Отримання компаній з блокноту
$notebook_companies = array();
if (count($_SESSION['notebook']) > 0) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['notebook']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id IN ($placeholders)");
    $stmt->execute($_SESSION['notebook']);
    $notebook_companies = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Блокнот</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Головна</a></li>
            <li><a href="notebook.php">Блокнот</a></li>
        </ul>
    </nav>

    <h1>Ваш Блокнот</h1>

    <?php if (isset($_GET['message'])): ?>
        <p class="message"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <?php if (count($notebook_companies) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Назва</th>
                    <th>Опис</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notebook_companies as $company): ?>
                    <tr>
                        <td>
                            <?php if ($company['photo']): ?>
                                <img src="../assets/uploads/<?php echo htmlspecialchars($company['photo']); ?>" alt="Фото компанії" width="100">
                            <?php else: ?>
                                Немає фото
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($company['name']); ?></td>
                        <td><?php echo htmlspecialchars($company['description']); ?></td>
                        <td>
                            <a href="company.php?id=<?php echo $company['id']; ?>" class="button">Переглянути</a> |
                            <a href="notebook.php?action=remove&id=<?php echo $company['id']; ?>" class="button button-danger">Видалити</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ваш блокнот порожній.</p>
    <?php endif; ?>

    <a href="index.php" class="button">Назад до Списку</a>
</body>
</html>
