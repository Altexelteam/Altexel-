<?php
include 'header.php';

if (!isset($_SESSION['username'])) {
    header("Location: login_register.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['video']['name'])) {
        $allowed_types = ['video/mp4', 'video/webm', 'video/ogg'];
        $file_type = $_FILES['video']['type'];

        if (!in_array($file_type, $allowed_types)) {
            $message = "Yalnız MP4, WebM və Ogg formatlı videolar qəbul edilir.";
        } else {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            $filename = uniqid() . "_" . basename($_FILES['video']['name']);
            $target = "uploads/" . $filename;

            if (move_uploaded_file($_FILES['video']['tmp_name'], $target)) {
                // Videonun məlumatını saxla
                $video_data = [
                    'user' => $_SESSION['username'],
                    'filename' => $filename,
                    'uploaded_at' => date('Y-m-d H:i:s'),
                ];

                $videos_file = 'videos.json';
                $videos = [];

                if (file_exists($videos_file)) {
                    $videos = json_decode(file_get_contents($videos_file), true);
                }

                $videos[] = $video_data;
                file_put_contents($videos_file, json_encode($videos, JSON_PRETTY_PRINT));

                $message = "Video uğurla yükləndi!";
            } else {
                $message = "Video yüklənərkən xəta baş verdi.";
            }
        }
    } else {
        $message = "Zəhmət olmasa, video faylı seçin.";
    }
}
?>

<h1>Video Yüklə</h1>

<?php if ($message): ?>
    <p style="color: <?=strpos($message, 'uğurla') !== false ? 'green' : 'red'?>; font-weight: bold;">
        <?=htmlspecialchars($message)?>
    </p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="video" accept="video/*" required style="margin-bottom: 15px;">
    <br>
    <button type="submit" class="btn">Yüklə</button>
</form>

<?php include 'footer.php'; ?>
