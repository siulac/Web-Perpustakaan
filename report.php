<?php
include 'koneksi.php';
require 'vendor/autoload.php';
if (!isset($_SESSION['logged_in'])) header("Location: index.php");

$basePath = $_SERVER['DOCUMENT_ROOT'];
$logoPath = $basePath . '/assets/library-logo.png';

$logoData = base64_encode(file_get_contents($logoPath));
$logoMime = mime_content_type($logoPath);
$logoSrc = 'data:' . $logoMime . ';base64,' . $logoData;

$stmt = $pdo->query("SELECT * FROM buku");
$books = $stmt->fetchAll();

$html = '<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 20px; }
        body { 
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(45deg, #f3f4f6, #ffffff);
            color: #1a1a1a;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #6c5ce7;
            border-radius: 15px;
            color: white;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px;
            border: 1px solid #e0e0e0;
            text-align: left;
        }
        th {
            background: #6c5ce7;
            color: white;
            border-color: #5d4ec7;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .cover-img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .no-cover {
            width: 60px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            border: 1px dashed #ccc;
            border-radius: 4px;
            color: #888;
            font-size: 12px;
            text-align: center;
            padding: 5px;
        }
        .genre-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            background: #6c5ce715;
            color: #6c5ce7;
            border: 1px solid #6c5ce730;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="' . $logoSrc . '" class="logo">
        <div>
            <div class="title">Library Collection Report</div>
            <div class="date">Generated: ' . date('F j, Y H:i') . '</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Year</th>
            </tr>
        </thead>
        <tbody>';

foreach ($books as $book) {
    $coverContent = '<div class="no-cover">No Cover</div>';
    if (!empty($book['cover'])) {
        $coverPath = $basePath . '/uploads/' . $book['cover'];
        if (file_exists($coverPath)) {
            $coverData = base64_encode(file_get_contents($coverPath));
            $coverMime = mime_content_type($coverPath);
            $coverContent = '<img src="data:' . $coverMime . ';base64,' . $coverData . '" class="cover-img">';
        }
    }

    $genre = !empty($book['genre']) ? $book['genre'] : 'No genre';

    $html .= '<tr>
                <td>' . $book['id'] . '</td>
                <td>' . $coverContent . '</td>
                <td>' . htmlspecialchars($book['judul']) . '</td>
                <td>' . htmlspecialchars($book['pengarang']) . '</td>
                <td><span class="genre-badge">' . htmlspecialchars($genre) . '</span></td>
                <td>' . ($book['tahun_terbit'] ?: '-') . '</td>
            </tr>';
}

$html .= '</tbody>
    </table>
</body>
</html>';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('defaultFont', 'Arial');
$dompdf->set_base_path($basePath);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("library-report-" . date('Y-m-d') . ".pdf", ["Attachment" => true]);
