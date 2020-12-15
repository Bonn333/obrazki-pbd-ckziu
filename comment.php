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

if(!isset($_POST['content']) || strlen($_POST['content']) > 320 || empty($_POST['content'])) {
    die('Niepoprawna treść komentarza');
}

$stmt = $pdo->prepare('INSERT INTO comments (image_id, content, created_at, ip) VALUES(:image_id, :content, :created_at, :ip)');
$stmt->bindValue('image_id', $image['id']);
$stmt->bindValue('content', $_POST['content']);
$stmt->bindValue('created_at', time());
$stmt->bindValue('ip', $_SERVER['REMOTE_ADDR']);
$stmt->execute();

header("Location: /photo.php?id={$image['id']}");