<?php
include 'koneksi.php';
if (!isset($_SESSION['logged_in'])) header("Location: index.php");

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'ASC';
$genreFilter = '';

if (!empty($_GET['genres'])) {
    $placeholders = rtrim(str_repeat('?,', count($_GET['genres'])), ',');
    $genreFilter = " AND genre IN ($placeholders)";
}

$query = "SELECT * FROM buku 
          WHERE (judul LIKE ? OR pengarang LIKE ?) 
          $genreFilter
          ORDER BY $sort $order";

$params = array_merge(["%$search%", "%$search%"], $_GET['genres'] ?? []);

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav>
        <h1>Library Dashboard</h1>
        <div class="nav-right">
            <span>Welcome, <?= $_SESSION['username'] ?></span>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </nav>

    <div class="container">
        <div class="action-bar">
            <a href="tambah_buku.php" class="btn btn-add">
                <i class="fas fa-plus-circle"></i> Add Book
            </a>

            <form action="" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search books..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <a href="report.php" class="btn btn-report">
                <i class="fas fa-file-pdf"></i> Generate Report
            </a>
        </div>

        <div class="filter-container">
            <div class="filter-title">Filter by Genre:</div>
            <form method="GET" class="genre-filter">
                <?php
                $genres = ['Fiction', 'Non-Fiction', 'Science', 'Technology', 'History', 'Art'];
                foreach ($genres as $genre) {
                    $checked = isset($_GET['genres']) && in_array($genre, $_GET['genres']) ? 'checked' : '';
                    echo '
                    <div class="genre-item">
                        <input type="checkbox" name="genres[]" id="genre_' . $genre . '" value="' . $genre . '" ' . $checked . '>
                        <label for="genre_' . $genre . '">' . $genre . '</label>
                    </div>';
                }
                ?>
                <button type="submit" class="btn btn-filter">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="<?= ($sort == 'id' ? 'active-sort' : '') ?>">
                        <a href="?sort=id&order=<?= ($sort == 'id' && $order == 'ASC') ? 'DESC' : 'ASC' ?>">
                            ID <i class="fas fa-sort<?= $sort == 'id' ? ($order == 'ASC' ? '-up' : '-down') : '' ?> sort-icon"></i>
                        </a>
                    </th>
                    <th class="<?= ($sort == 'judul' ? 'active-sort' : '') ?>">
                        <a href="?sort=judul&order=<?= ($sort == 'judul' && $order == 'ASC') ? 'DESC' : 'ASC' ?>">
                            Title <i class="fas fa-sort<?= $sort == 'judul' ? ($order == 'ASC' ? '-up' : '-down') : '' ?> sort-icon"></i>
                        </a>
                    </th>
                    <th class="non-sortable">Cover</th>
                    <th class="<?= ($sort == 'pengarang' ? 'active-sort' : '') ?>">
                        <a href="?sort=pengarang&order=<?= ($sort == 'pengarang' && $order == 'ASC') ? 'DESC' : 'ASC' ?>">
                            Author <i class="fas fa-sort<?= $sort == 'pengarang' ? ($order == 'ASC' ? '-up' : '-down') : '' ?> sort-icon"></i>
                        </a>
                    </th>
                    <th class="<?= ($sort == 'genre' ? 'active-sort' : '') ?>">
                        <a href="?sort=genre&order=<?= ($sort == 'genre' && $order == 'ASC') ? 'DESC' : 'ASC' ?>">
                            Genre <i class="fas fa-sort<?= $sort == 'genre' ? ($order == 'ASC' ? '-up' : '-down') : '' ?> sort-icon"></i>
                        </a>
                    </th>
                    <th class="<?= ($sort == 'tahun_terbit' ? 'active-sort' : '') ?>">
                        <a href="?sort=tahun_terbit&order=<?= ($sort == 'tahun_terbit' && $order == 'ASC') ? 'DESC' : 'ASC' ?>">
                            Year <i class="fas fa-sort<?= $sort == 'tahun_terbit' ? ($order == 'ASC' ? '-up' : '-down') : '' ?> sort-icon"></i>
                        </a>
                    </th>
                    <th class="non-sortable">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= $book['id'] ?></td>
                        <td><?= htmlspecialchars($book['judul']) ?></td>
                        <td>
                            <?php if (!empty($book['cover'])): ?>
                                <img src="uploads/<?= $book['cover'] ?>" alt="Cover" class="book-cover">
                            <?php else: ?>
                                <div class="no-data">
                                    <i class="fas fa-image"></i>
                                    <span>No Cover</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($book['pengarang']) ?></td>
                        <td><?= $book['tahun_terbit'] ? $book['tahun_terbit'] : '<span class="no-data">No year</span>' ?></td>
                        <td><?= htmlspecialchars($book['genre']) ? htmlspecialchars($book['genre']) : '<span class="no-data">No genre</span>' ?></td>
                        <td>
                            <a href="edit_buku.php?id=<?= $book['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="hapus_buku.php?id=<?= $book['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>