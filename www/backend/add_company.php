<?php
require_once '../config/database.php';

// Включення відображення помилок для налагодження (тимчасово)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Валідація та обробка даних
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $contact = isset($_POST['contact']) ? $_POST['contact'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $contact_person = isset($_POST['contact_person']) ? $_POST['contact_person'] : '';
    $certificate_number = isset($_POST['certificate_number']) ? $_POST['certificate_number'] : '';
    $tax_id = isset($_POST['tax_id']) ? $_POST['tax_id'] : '';
    $certificate_status = isset($_POST['certificate_status']) ? $_POST['certificate_status'] : 'inactive';

    // Обробка завантаження фото
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Дозволені розширення
        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Унікальне ім'я файлу
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../assets/uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $photo = $newFileName;
            } else {
                $photo = null;
            }
        } else {
            $photo = null;
        }
    } else {
        $photo = null;
    }

    // Підготовка та виконання запиту
    $sql = "INSERT INTO companies (name, description, contact, email, contact_person, certificate_number, tax_id, certificate_status, photo, updated_at)
            VALUES (:name, :description, :contact, :email, :contact_person, :certificate_number, :tax_id, :certificate_status, :photo, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $name,
        ':description' => $description,
        ':contact' => $contact,
        ':email' => $email,
        ':contact_person' => $contact_person,
        ':certificate_number' => $certificate_number,
        ':tax_id' => $tax_id,
        ':certificate_status' => $certificate_status,
        ':photo' => $photo
    ));

    //Перенаправлення на сторінку
    header("Location: ../frontend/index.php?message=Компанію успішно додано");
    exit();
}
?>
