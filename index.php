<?php
include 'header.php';

$videos_file = 'videos.json';
$videos = [];

if (file_exists($videos_file)) {
    $videos = json_decode(file_get_contents($videos_file), true);
}
?>

<h1>Bizim haqqımızda</h1>

<p>
Altexel platforması sizin video paylaşım, yazışma və sosial əlaqə imkanlarınızı bir araya gətirir.  
Məqsədimiz istifadəçilərə rahat, təhlükəsiz və innovativ mühit yaratmaqdır.  
Burada videolarınızı paylaşa, dostlarınızla yazışa və fikirlərinizi sərbəst ifadə edə bilərsiniz.
</p>

<p>
Platformamızın rəngi yaşıl və ağdır — təbiətin təravətini və sakitliyini simvolizə edir.  
İstifadəçi dostu interfeysimiz ilə sizə yüksək keyfiyyətli təcrübə təqdim etməyə çalışırıq.
</p>

<h2>Son yüklənmiş videolar</h2>

<?php if (count($videos) === 0): ?>
    <p>Hələ video yüklənməyib.</p>
<?php else: ?>
    <?php foreach (array_reverse($videos) as $video): ?>
        <div style="margin-bottom: 30px; border: 1px solid #3c9d3c; padding: 15px; border-radius: 8px; background: #f9fff9;">
            <video width="100%" controls>
                <source src="uploads/<?=htmlspecialchars($video['filename'])?>" type="video/mp4">
                Brauzeriniz video formatını dəstəkləmir.
            </video>
            <p><strong>Yükləyən:</strong> <?=htmlspecialchars($video['user'])?></p>
            <p><strong>Tarix:</strong> <?=htmlspecialchars($video['uploaded_at'])?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'footer.php'; ?>
