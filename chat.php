<?php
include 'header.php';

// Giriş yoxlanışı
if (!isset($_SESSION['username'])) {
    header("Location: login_register.php");
    exit;
}

$chat_file = "messages/chat.txt";
$messages = [];

if (file_exists($chat_file)) {
    $lines = file($chat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $messages[] = json_decode($line, true);
    }
}
?>

<h1>Altexel Sohbet</h1>

<div id="chat-box" style="border: 1px solid #3c9d3c; background: #eaf5ea; height: 300px; overflow-y: scroll; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
    <?php foreach ($messages as $msg): ?>
        <p>
            <strong style="color: #2c5d2f;"><?=htmlspecialchars($msg['user'])?></strong>:
            <?=htmlspecialchars($msg['message'])?>
            <span style="color: #999; font-size: 0.8em;">(<?=$msg['time']?>)</span>
        </p>
    <?php endforeach; ?>
</div>

<form id="chat-form">
    <input type="text" id="message" name="message" placeholder="Mesajınızı yazın..." required
           style="width: 80%; padding: 8px; border: 1px solid #3c9d3c; border-radius: 5px;">
    <button type="submit" class="btn" style="width: 18%;">Göndər</button>
</form>

<script>
const form = document.getElementById('chat-form');
const chatBox = document.getElementById('chat-box');

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const messageInput = document.getElementById('message');
    const message = messageInput.value.trim();

    if (!message) return;

    const formData = new FormData();
    formData.append('message', message);

    const response = await fetch('send_message.php', {
        method: 'POST',
        body: formData
    });

    const text = await response.text();
    if (response.ok) {
        // Mesaj uğurla göndərildisə, chat sahəsini yenilə və inputu təmizlə
        messageInput.value = '';
        loadMessages();
    } else {
        alert('Mesaj göndərilmədi: ' + text);
    }
});

async function loadMessages() {
    const response = await fetch('load_messages.php');
    if (response.ok) {
        const messagesHtml = await response.text();
        chatBox.innerHTML = messagesHtml;
        chatBox.scrollTop = chatBox.scrollHeight;
    }
}

// Səhifə yüklənəndə mesajları yüklə
loadMessages();

// Mesajları hər 5 saniyədən bir avtomatik yenilə
setInterval(loadMessages, 5000);
</script>

<?php
include 'footer.php';
?>
