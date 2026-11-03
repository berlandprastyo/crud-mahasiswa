<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include_once __DIR__ . "/config/config.php";
define('APP_ACCESS', true);
$no = 0;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $stmt = mysqli_prepare($conn,'DELETE FROM mahasiswa WHERE id=?');
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
    header("Location: index.php");
    exit;
    }
    $search = trim($_GET['search'] ?? '');
    $prodi = $_GET['prodi'] ?? '';
    $keyword = "%" . $search . "%";
    $stmt = mysqli_prepare(
        $conn, 
        "SELECT * FROM mahasiswa WHERE ('' = ? OR prodi = ?) AND ('' = ? OR nama LIKE ? OR npm LIKE ?)"
    );
    mysqli_stmt_bind_param($stmt,"sssss",$prodi,$prodi,$search,$keyword,$keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PENGELOLAAN DATA MAHASISWA</title>
      <link rel="icon" type="image/png" href="<?=htmlspecialchars($logo); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
     <?php include __DIR__ . "/include/header.php";?>
    <!-- <?php  echo ($_SESSION['login']); echo ($_SESSION['username']); ?> -->
     <div class="container-table">

         <table>
             <thead>
                 <tr>
                     <th>NO</th>
                     <th>NPM</th>
                     <th>NAMA</th>
                     <th>PRODI</th>
                     <th>ANGKATAN</th>
                     <th>EMAIL</th>
                     <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) {
            $no++;
            ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $row['npm']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['prodi']; ?></td>
                    <td><?= $row['angkatan']; ?></td>
            <td><?= $row['email']; ?></td>
            <td> 
                <div class="action-group">
                    <form action="update.php" method="GET">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" class="btn-edit">UBAH DATA</button>
                    </form>
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" class="btn-delete">HAPUS DATA</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>
</div>
</body>
</html>