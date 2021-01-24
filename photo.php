<?php $pdo = require 'database.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Podgląd obrazka</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/css/theme.css"/>
</head>
<body>

<div class="container text-center">
    <?php
        if(isset($_GET['id'])) {
            $images = $pdo->prepare('SELECT * FROM images WHERE id = :id');
            $images->bindValue('id', $_GET['id']);
            $images->execute();
            $image = $images->fetch(PDO::FETCH_ASSOC);
        } else {
            $image = false;
        }
    ?>

    <?php if($image) { ?>
        <h2>Obrazek <?= $image['id']; ?></h2>

        <a href="/">Wróć do strony głównej</a>

        <hr/>

        <div class="thumbnail big-thumbnail">
            <a href="/photo.php?id=<?= $image['id']; ?>">
                <img src="<?= $image['photo_url']; ?>" alt="Wpis o ID <?= $image['id']; ?>" style="width: 50%; height: 50%"/>
            </a>
        </div>

        <?php
            $comments = $pdo->prepare('SELECT COUNT(id) FROM comments WHERE image_id = :image_id');
            $comments->bindValue('image_id', $image['id']);
            $comments->execute();

            $ratings = $pdo->prepare('SELECT COUNT(id) FROM ratings WHERE image_id = :image_id');
            $ratings->bindValue('image_id', $image['id']);
            $ratings->execute();

            $ratings2 = $pdo->prepare('SELECT AVG(score) FROM ratings WHERE image_id = :image_id');
            $ratings2->bindValue('image_id', $image['id']);
            $ratings2->execute();
        ?>

        <span class="badge">Komentarze: <?= (int)$comments->fetchColumn(); ?></span>
        <span class="badge">Liczba ocen: <?= (int)$ratings->fetchColumn(); ?></span>
        <span class="badge">Średnia ocena: <?= number_format($ratings2->fetchColumn(), 1); ?></span>

        <hr/>

        <div class="row text-left">
            <div class="col-lg-8 col-lg-offset-2">
                <h4>Oceń obrazek</h4>

                <?php
                    $lastRatings = $pdo->prepare('SELECT * FROM ratings WHERE image_id = :image_id AND ip = :ip AND UNIX_TIMESTAMP(NOW()) - created_at < 60 ORDER BY id DESC');
                    $lastRatings->bindValue('image_id', $image['id']);
                    $lastRatings->bindValue('ip', $_SERVER['REMOTE_ADDR']);
                    $lastRatings->execute();
                ?>

                <?php if($lastRatings->rowCount() === 0) { ?>
                    <form action="/rate.php" method="post" class="form-inline">
                        <input type="hidden" name="image_id" value="<?= $image['id']; ?>"/>

                        <div class="form-group">
                            <label for="rate_score" class="control-label">Wybierz ocenę:</label>
                            <select name="score" id="rate_score" class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Wyślij</button>
                    </form>
                <?php } else { ?>
                    <?php $lastRating = $lastRatings->fetch(PDO::FETCH_ASSOC); ?>
                    <div class="alert alert-info">Zablokowano możliwość oceniania tego obrazka na 60 sekund, do <?= date('d-m-Y H:i:s', $lastRating['created_at'] + 60); ?></div>
                <?php } ?>
            </div>
        </div>

        <hr/>

        <div class="row text-left">
            <div class="col-lg-8 col-lg-offset-2">
                <h4>Dodaj komentarz</h4>

                <?php
                    $lastComments = $pdo->prepare('SELECT * FROM comments WHERE image_id = :image_id AND ip = :ip AND UNIX_TIMESTAMP(NOW()) - created_at < 60 ORDER BY id DESC');
                    $lastComments->bindValue('image_id', $image['id']);
                    $lastComments->bindValue('ip', $_SERVER['REMOTE_ADDR']);
                    $lastComments->execute();
                ?>

                <?php if($lastComments->rowCount() === 0) { ?>
                    <form action="/comment.php" method="post">
                        <input type="hidden" name="image_id" value="<?= $image['id']; ?>"/>

                        <div class="form-group">
                            <label for="comment_content" class="control-label">Treść komentarza:</label>
                            <textarea name="content" id="comment_content" class="form-control" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Wyślij</button>
                    </form>
                <?php } else { ?>
                    <?php $lastComment = $lastComments->fetch(PDO::FETCH_ASSOC); ?>
                    <div class="alert alert-info">Zablokowano możliwość komentowania tego obrazka na 60 sekund, do <?= date('d-m-Y H:i:s', $lastComment['created_at'] + 60); ?></div>
                <?php } ?>
            </div>
        </div>

        <hr/>

        <div class="row text-left">
            <div class="col-lg-8 col-lg-offset-2">
                <h4>Komentarze</h4>

                <?php
                    $comments = $pdo->prepare('SELECT * FROM comments WHERE image_id = :image_id ORDER BY id DESC');
                    $comments->bindValue('image_id', $image['id']);
                    $comments->execute();
                ?>

                <?php foreach($comments->fetchAll(PDO::FETCH_ASSOC) as $comment) { ?>
                    <div class="comment">
                        <p><?= htmlspecialchars($comment['content']); ?></p>
                        <p class="comment-author">
                            Dodano: <b><?= date('d-m-Y H:i:s', $comment['created_at']); ?></b>
                            IP autora: <b><?= $comment['ip']; ?></b>
                        </p>
                    </div>
                <?php } ?>

                <?php if($comments->rowCount() === 0) { ?>
                    <div class="alert alert-info">Nie znaleziono żadnych komentarzy.</div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">Nie znaleziono żądanego obrazka.</div>
    <?php } ?>
</div>

</body>
</html>