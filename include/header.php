<?php
if (!defined('APP_ACCESS')) {
    http_response_code(403);
    die("Forbidden");
}
include_once __DIR__ . "/../config/config.php";
$prodiResult = mysqli_query(
    $conn,
    "SELECT DISTINCT prodi FROM mahasiswa ORDER BY prodi ASC"
);

$search = trim($_GET['search'] ?? '');
$prodi = $_GET['prodi'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="navbar-header">
            <img src="<?=htmlspecialchars($logo); ?>" alt="">
                    <div class="navbar-title">
                    <h1><?=htmlspecialchars($header);?></h1>
                    <p>SISTEM PENGELOLAAN DATA MAHASISWA</p>
                </div>
        </div>
         <div class="navbar-search">
                <a href="logout.php">LOGOUT</a>
            <form action="index.php" method="GET">
                <select name="prodi" class="filter-prodi">
                    <option value="">SEMUA PRODI</option>
                    
                    <?php while ($p = mysqli_fetch_assoc($prodiResult)) { ?>
                    <option value="<?= htmlspecialchars($p['prodi']); ?>"
                    <?= ($prodi === $p['prodi']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($p['prodi']); ?>
                </option>
                <?php } ?>
            </select>
            <input type="text" name="search" placeholder="Cari" value="<?=htmlspecialchars($search);?>">
            <button type="submit">Cari</button>
        </form>
    </div>
    
    <nav>
        <div class="nav-left">
            <a href="index.php">DASHBOARD</a>
            <?php if ($_SESSION['username'] == "admin") { ?>
                
            
            <a href="tambah.php">TAMBAH MAHASISWA</a> <?php } ?>
        </div>
        <a href="ganti.php">GANTI PASSWORD</a>
    </nav>
</header>
</body>
</html>
