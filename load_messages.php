<?php
session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit("Giriş lazımdır");
}

$chat_file = "messages/chat.txt";
if (!file_exists($chat_file)) {
    exit('');
}

$lines = file($chat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    $msg = json_decode($line, true);
    if ($msg) {
        echo "<p><strong style='color: #2c5d2f;'>" . htmlspecialchars($msg['user']) . "</strong>: "
            . htmlspecialchars($msg['message'])
            . " <span style='color: #999; font-size: 0.8em;'>(" . $msg['time'] . ")</span></p>";
    }
}
?>
