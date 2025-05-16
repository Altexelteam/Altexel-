<?php
include 'header.php';

$show_login = true; // hansı forma göstərilsin? Default login

if (isset($_GET['register'])) {
    $show_login = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Register forması
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $user_file = "users/$username.json";

        if (file_exists($user_file)) {
            echo "<p style='color:red;'>İstifadəçi adı artıq mövcuddur!</p>";
        } else {
            $user_data = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];
            if (!is_dir('users')) {
                mkdir('users', 0777, true);
            }
            file_put_contents($user_file, json_encode($user_data));
            echo "<p style='color:green;'>Qeydiyyat uğurludur. <a href='login_register.php'>Daxil ol</a></p>";
        }
    }

    // Login forması
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $user_file = "users/$username.json";

        if (!file_exists($user_file)) {
            echo "<p style='color:red;'>İstifadəçi tapılmadı!</p>";
        } else {
            $user_data = json_decode(file_get_contents($user_file), true);
            if (password_verify($password, $user_data['password'])) {
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                echo "<p style='color:red;'>Şifrə yanlışdır!</p>";
            }
        }
    }
}
?>

<div style="max-width: 400px; margin: auto;">
    <div style="text-align:center; margin-bottom: 15px;">
        <button onclick="showLogin()" class="btn" style="margin-right: 10px;">Daxil ol</button>
        <button onclick="showRegister()" class="btn">Qeydiyyat</button>
    </div>

    <?php if ($show_login): ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="İstifadəçi adı" required style="width: 100%; margin-bottom:10px; padding: 8px;">
            <input type="password" name="password" placeholder="Şifrə" required style="width: 100%; margin-bottom:10px; padding: 8px;">
            <button type="submit" name="login" class="btn" style="width: 100%;">Daxil ol</button>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="İstifadəçi adı" required style="width: 100%; margin-bottom:10px; padding: 8px;">
            <input type="password" name="password" placeholder="Şifrə" required style="width: 100%; margin-bottom:10px; padding: 8px;">
            <button type="submit" name="register" class="btn" style="width: 100%;">Qeydiyyat</button>
        </form>
    <?php endif; ?>
</div>

<script>
function showLogin() {
    window.location.href = 'login_register.php';
}
function showRegister() {
    window.location.href = 'login_register.php?register=1';
}
</script>

<?php
include 'footer.php';
?>
