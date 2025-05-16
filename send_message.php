<?php
session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit("Giriş lazımdır");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');

    if (!$message) {
        http_response_code(400);
        exit("Mesaj boş ola bilməz");
    }

    $chat_file = "messages/chat.txt";

    $entry = json_encode([
        'user' => $_SESSION['username'],
        'message' => htmlspecialchars($message),
        'time' => date("Y-m-d H:i:s")
    ]) . PHP_EOL;

    file_put_contents($chat_file, $entry, FILE_APPEND | LOCK_EX);

    echo "Mesaj göndərildi";
}
?>
