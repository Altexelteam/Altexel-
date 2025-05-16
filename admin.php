<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Videoları oxu
$videos_file = 'videos.json';
$videos = [];
if (file_exists($videos_file)) {
    $videos = json_decode(file_get_contents($videos_file), true);
}

// Mesajlar faylı
$chat_file = 'messages/chat.txt';
$messages = [];
if (file_exists($chat_file)) {
    $lines = file($chat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $messages[] = json_decode($line, true);
    }
}

// Mesaj və video silmə funksiyaları
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Video silmə
    if (isset($_POST['delete_video'])) {
        $index = (int)$_POST['video_index'];
        if (isset($videos[$index])) {
            // Videonu fayldan sil
            $file_to_delete = 'uploads/' . $videos[$index]['filename'];
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }
            // Videonu array-dən sil
            array_splice($videos, $index, 1);
            file_put_contents($videos_file, json_encode($videos, JSON_PRETTY_PRINT));
            $msg = "Video uğurla silindi.";
        }
    }

    // Mesaj silmə
    if (isset($_POST['delete_message'])) {
        $index = (int)$_POST['message_index'];
        if (isset($messages[$index])) {
            array_splice($messages, $index, 1);
            // Mesajları yenidən fayla yaz
            $lines = [];
            foreach ($messages as $m) {
                $lines[] = json_encode($m);
            }
            file_put_contents($chat_file, implode(PHP_EOL, $lines) . PHP_EOL);
            $msg = "Mesaj uğurla silindi.";
        }
    }
}

include 'header.php';

if (isset($msg)) {
    echo "<div class='alert alert-success'>{$msg}</div>";
}
?>

<h1>Admin Panel</h1>

<h2>Videoları idarə et</h2>

<?php if (count($videos) === 0): ?>
    <p>Hələ heç bir video yüklənməyib.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Video</th>
                <th>Yükləyən</th>
                <th>Tarix</th>
                <th>Əməliyyatlar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($videos as $i => $video): ?>
                <tr>
                    <td>
                        <video width="200" controls>
                            <source src="uploads/<?=htmlspecialchars($video['filename'])?>" type="video/mp4">
                            Brauzeriniz video formatını dəstəkləmir.
                        </video>
                    </td>
                    <td><?=htmlspecialchars($video['user'])?></td>
                    <td><?=htmlspecialchars($video['uploaded_at'])?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Videonu silmək istəyirsiniz?');" style="display:inline;">
                            <input type="hidden" name="video_index" value="<?=$i?>" />
                            <button type="submit" name="delete_video" class="btn">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


<h2 style="margin-top: 40px;">Mesajları idarə et</h2>

<?php if (count($messages) === 0): ?>
    <p>Hələ heç bir mesaj yoxdur.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>İstifadəçi</th>
                <th>Mesaj</th>
                <th>Tarix</th>
                <th>Əməliyyatlar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $i => $msgItem): ?>
                <tr>
                    <td><?=htmlspecialchars($msgItem['user'])?></td>
                    <td><?=htmlspecialchars($msgItem['message'])?></td>
                    <td><?=htmlspecialchars($msgItem['time'])?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Mesajı silmək istəyirsiniz?');" style="display:inline;">
                            <input type="hidden" name="message_index" value="<?=$i?>" />
                            <button type="submit" name="delete_message" class="btn">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p style="margin-top: 40px;">
    <a href="admin_logout.php" class="btn" style="background: #a33;">Çıxış</a>
</p>

<?php include 'footer.php'; ?>
