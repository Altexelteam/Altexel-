<?php
session_start();

$admin_username = 'admin';
$admin_password = 'admin123'; // burda istəyinə uyğun dəyişə bilərsən

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === $admin_username && $pass === $admin_password) {
        $_SESSION['is_admin'] = true;
        $_SESSION['username'] = 'admin';
        header("Location: admin.php");
        exit;
    } else {
        $error = "İstifadəçi adı və ya şifrə yalnışdır.";
    }
}
?>

<h1>Admin Panel Girişi</h1>

<?php if ($error): ?>
    <div class="alert alert-error"><?=htmlspecialchars($error)?></div>
<?php endif; ?>

<form method="POST" style="max-width: 400px; margin: auto;">
    <label>İstifadəçi adı:</label>
    <input type="text" name="username" required />

    <label>Şifrə:</label>
    <input type="password" name="password" required />

    <button type="submit">Daxil ol</button>
</form>
