<?php
include 'koneksi.php';
if (!isset($_SESSION['logged_in'])) header("Location: index.php");

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if ($book) {
    if ($book['cover'] && file_exists("uploads/{$book['cover']}")) {
        unlink("uploads/{$book['cover']}");
    }
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: dashboard.php");
exit;
