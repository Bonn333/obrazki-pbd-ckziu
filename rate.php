<?php

$pdo = require 'database.php';

if(isset($_POST['image_id'])) {
    $images = $pdo->prepare('SELECT * FROM images WHERE id = :id');
    $images->bindValue('id', $_POST['image_id']);
    $images->execute();
    $image = $images->fetch(PDO::FETCH_ASSOC);
} else {
    $image = false;
}

if(!$image) {
    die('Nie znaleziono obrazka');
}

if(!isset($_POST['score']) || $_POST['score'] < 0 || $_POST['score'] > 5) {
    die('Niepoprawna ocena');
}

$stmt = $pdo->prepare('INSERT INTO ratings (image_id, score, ip, created_at) VALUES(:image_id, :score, :ip, :created_at)');
$stmt->bindValue('image_id', $image['id']);
$stmt->bindValue('score', $_POST['score']);
$stmt->bindValue('created_at', time());
$stmt->bindValue('ip', $_SERVER['REMOTE_ADDR']);
$stmt->execute();

header("Location: /photo.php?id={$image['id']}");