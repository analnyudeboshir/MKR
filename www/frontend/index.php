<?php
// frontend/index.php
require_once '../config/database.php';
header('Content-Type: text/html; charset=utf-8');
// Включення відображення помилок для налагодження (тимчасово)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Отримання значення 'search' з GET-запиту або встановлення за замовчуванням
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    // Використання унікальних параметрів :search1 і :search2
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE name LIKE :search1 OR description LIKE :search2");
    $stmt->execute(array(
        ':search1' => "%" . $search . "%",
        ':search2' => "%" . $search . "%"
    ));
} else {
    $stmt = $pdo->query("SELECT * FROM companies");
}
$companies = $stmt->fetchAll();

// Ініціалізація сесії для доступу до блокноту
session_start();
if (!isset($_SESSION['notebook'])) {
    $_SESSION['notebook'] = array();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>База Компаній</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <nav>
        <ul>
            <li><a href="index.php">Головна</a></li>
            <li><a href="notebook.php">Блокнот</a></li>
        </ul>
    </nav>

    <h1>Пошукова База Компаній</h1>

    <?php if (isset($_GET['message'])): ?>
        <p class="message"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Пошук компаній..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Пошук</button>
    </form>


    <table>
        <thead>
            <tr>
                <th>Фото</th>
                <th>Назва</th>
                <th>Опис</th>
                <th>Контакт</th>
                <th>Email</th>
                <th>Контактна Особа</th>
                <th>Номер Сертифікату</th>
                <th>ІПН</th>
                <th>Статус Сертифікату</th>
                <th>Дії</th>
                <th>Соцмережі</th>
                <th></th>
                
            </tr>
        </thead>
        <tbody>
            <?php if (count($companies) > 0): ?>
                <?php foreach ($companies as $company): ?>
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
                        <td><?php echo htmlspecialchars($company['contact']); ?></td>
                        <td><?php echo htmlspecialchars($company['email']); ?></td>
                        <td><?php echo htmlspecialchars($company['contact_person']); ?></td>
                        <td><?php echo htmlspecialchars($company['certificate_number']); ?></td>
                        <td><?php echo htmlspecialchars($company['tax_id']); ?></td>
                        <td><?php echo htmlspecialchars($company['certificate_status']); ?></td>
                        <td>
                            <a href="edit_form.php?id=<?php echo $company['id']; ?>">Редагувати</a> |
                            <a href="../backend/delete_company.php?id=<?php echo $company['id']; ?>" onclick="return confirm('Ви впевнені?')">Видалити</a>
                        </td>

                        <td>
                            <div class="social-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://yourdomain.com/frontend/company.php?id=' . $company['id']); ?>" target="_blank">Facebook</a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://yourdomain.com/frontend/company.php?id=' . $company['id']); ?>&text=<?php echo urlencode($company['name']); ?>" target="_blank" class="twitter">Twitter</a>
                            </div>
                        </td>
                        <td>
                            <?php if (in_array($company['id'], $_SESSION['notebook'])): ?>
                                <span class="button button-success">Вже в Блокноті</span>
                            <?php else: ?>
                                <a href="notebook.php?action=add&id=<?php echo $company['id']; ?>" class="button">Додати до Блокноту</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">Компанії не знайдені.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Додати Нову Компанію</h2>
    <form action="../backend/add_company.php" method="POST" enctype="multipart/form-data">
        <label>Назва:
            <input type="text" name="name" required>
        </label>
        <label>Опис:
            <textarea name="description"></textarea>
        </label>
        <label>Контакт:
            <input type="text" name="contact">
        </label>
        <label>Email:
            <input type="email" name="email">
        </label>
        <label>Контактна Особа:
            <input type="text" name="contact_person">
        </label>
        <label>Номер Сертифікату:
            <input type="text" name="certificate_number">
        </label>
        <label>ІПН:
            <input type="text" name="tax_id">
        </label>
        <label>Статус Сертифікату:
            <select name="certificate_status">
                <option value="active">Активний</option>
                <option value="inactive">Неактивний</option>
            </select>
        </label>
        <label>Фото:
            <input type="file" name="photo">
        </label>
        <button type="submit">Додати Компанію</button>
    </form>

    <a href="notebook.php" class="button">Перейти до Блокноту</a>
</body>
</html>
