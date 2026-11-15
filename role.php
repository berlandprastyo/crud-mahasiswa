<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
include_once __DIR__ . "/config/config.php";
define('APP_ACCESS', true);
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
$no = 0;
$role = mysqli_query($conn,"SELECT * FROM users");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    if ($id == (int)$_SESSION['id']) {
        $_SESSION['pesan'] = "Tidak Bisa Menghapus Akun Anda Sendiri!";
    }
    else {
        $stmt = mysqli_prepare($conn,"DELETE FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt,"i",$id);
        if (mysqli_stmt_execute($stmt)){
        $_SESSION['pesan'] = "Berhasil Menghapus User!";
        } else {
        $_SESSION['pesan'] = "Gagal Menghapus User!";
        }
       

    }
    header("Location: role.php");
    exit;
}
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
      <?php if ($pesan != "") { ?>
    <div class="alert">
        <?= htmlspecialchars($pesan); ?>
    </div>
<?php } ?>
     <div class="container-table">

         <table>
             <thead>
                 <tr>
                     <th>NO</th>
                     <th>NAMA</th>
                     <th>ROLE</th>
                     <?php if ($_SESSION['role'] == "admin") { ?>
                     <th>ACTION</th>
                      <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($role)) { 
        $no++; ?>
    <tr>
        <td><?=$row['id']; ?></td>
        <td><?=$row['username']; ?></td>
        <td><?=$row['role']; ?></td>
         <?php if ($_SESSION['role'] == "admin") { ?>
         <td>
                <div class="action-group">
                    <form action="ubah.php" method="GET">
                        <input type="hidden" name="id" value="<?=$row['id']; ?>">
                        <button type="submit" class="btn-edit">UBAH ROLE</button>
                    </form>
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" class="btn-delete">HAPUS USER</button>
                    </form>
                </div>
            </td>
             <?php } ?>
        <?php } ?>
    </tr>
    </tbody>
</table>
</div>
</body>
</html>