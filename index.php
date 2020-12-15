<?php $pdo = require 'database.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Obrazki</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/css/theme.css"/>
</head>
<body>

<div class="container text-center">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <h2>Wszystkie obrazki</h2>

            <hr/>

            <?php $images = $pdo->query('SELECT * FROM images ORDER BY id DESC LIMIT 15'); ?>

            <?php foreach($images->fetchAll(PDO::FETCH_ASSOC) as $image) { ?>
                <div class="entry">
                    <div class="thumbnail">
                        <a href="/photo.php?id=<?= $image['id']; ?>">
                            <img src="<?= $image['photo_url']; ?>" alt="Wpis o ID <?= $image['id']; ?>"/>
                        </a>
                    </div>

                    <?php
                        $comments = $pdo->prepare('SELECT COUNT(id) FROM comments WHERE image_id = :image_id');
                        $comments->bindValue('image_id', $image['id']);
                        $comments->execute();

                        $ratings = $pdo->prepare('SELECT COUNT(id) FROM ratings WHERE image_id = :image_id');
                        $ratings->bindValue('image_id', $image['id']);
                        $ratings->execute();
                    ?>

                    <span class="badge">Komentarze: <?= (int)$comments->fetchColumn(); ?></span>
                    <span class="badge">Liczba ocen: <?= (int)$ratings->fetchColumn(); ?></span>
                </div>
            <?php } ?>

            <?php if($images->rowCount() === 0) { ?>
                <div class="alert alert-warning">Nie znaleziono żadnych wpisów.</div>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>