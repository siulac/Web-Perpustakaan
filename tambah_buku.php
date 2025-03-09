<?php
include 'koneksi.php';
if (!isset($_SESSION['logged_in'])) header("Location: index.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $tahun_terbit = $_POST['tahun_terbit'];


    $cover = '';
    if ($_FILES['cover']['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        $cover = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['cover']['tmp_name'], 'uploads/' . $cover);
    }

    $genre = $_POST['genre'];
    $stmt = $pdo->prepare("INSERT INTO buku (judul, pengarang, tahun_terbit, cover, genre) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$judul, $pengarang, $tahun_terbit, $cover, $genre]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav>
        <h1>Add New Book</h1>
        <a href="dashboard.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </nav>

    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Cover Image:</label>
                <div class="preview-container">
                </div>
                <div class="file-input-wrapper">
                    <div class="file-input">
                        <input type="file" name="cover" id="cover" accept="image/*">
                        <label for="cover" class="file-label">
                            <i class="fas fa-upload"></i> Choose File
                        </label>
                        <span class="file-name">No file chosen</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($book['judul'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Author:</label>
                <input type="text" name="pengarang" value="<?= htmlspecialchars($book['pengarang'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Genre:</label>
                <select name="genre" class="genre-select" required>
                    <option value="">Select Genre</option>
                    <?php
                    $genres = ['Fiction', 'Non-Fiction', 'Science', 'Technology', 'History', 'Art'];
                    foreach ($genres as $g) {
                        $selected = ($g == ($book['genre'] ?? '')) ? 'selected' : '';
                        echo "<option value='$g' $selected>$g</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Year:</label>
                <input type="number" name="tahun_terbit"
                    value="<?= $book['tahun_terbit'] ?? '' ?>"
                    required>
            </div>

            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i> <?= isset($book) ? 'Update Book' : 'Save Book' ?>
            </button>
        </form>
    </div>

    <script>
        document.getElementById('cover').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileName = document.querySelector('.file-name');
            const previewContainer = document.querySelector('.preview-container');

            previewContainer.innerHTML = '';

            if (file) {
                fileName.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            } else {
                fileName.textContent = 'No file chosen';
            }
        });
    </script>
</body>

</html>