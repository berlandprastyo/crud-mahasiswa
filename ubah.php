<?php 
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include_once __DIR__ . "/config/config.php";
define('APP_ACCESS', true);
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("ID tidak valid!");
} 
$id = (int)$_GET['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
if (!$row) {
     die("Data User Tidak Ada!");   
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['role'] !== "admin") {
        header("Location: index.php");
        exit;
    }   
    $username = trim($_POST["username"]);
    $role = trim($_POST["role"]);
    if ((int)$_SESSION['id'] == $id && $role != "admin") {
        $_SESSION['pesan'] = "Tidak Bisa Ubah Role Anda Sendiri!";
    }
    elseif (!preg_match("/^[a-zA-Z0-9_]{5,20}$/",$username)) {
        $_SESSION['pesan'] = "Username Yang Diperbolehkan 5-20 karakter dan Tanpa Spasi";
    }
    else {
    $stmt = mysqli_prepare($conn,"UPDATE users SET username = ?, role = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt,"ssi",$username,$role,$id);
    
    if (mysqli_stmt_execute($stmt)){
    $_SESSION['pesan'] = "Berhasil Update Data!";
    } else {
    $_SESSION['pesan'] = "Gagal Update Data!";
    }
    }
    header("Location: ubah.php?id=" . $id );
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UBAH DATA</title>
      <link rel="icon" type="image/png" href="<?=htmlspecialchars($logo); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
     <?php include  __DIR__ . "/include/header.php";?>
    <div class="container-update">
<div class="form-update">
 <?php if ($pesan != "") { ?>
    <div class="alert">
        <?= htmlspecialchars($pesan); ?>
    </div>
<?php } ?>
    <form action="" method="POST">
            <label for="username">USERNAME :</label>
            <input type="text" name="username" value="<?=htmlspecialchars($row['username']); ?>">
            <br>
            <label for="role">ROLE :</label>
           <select name="role" id="role" class="ubah">
            <option value="admin" <?= ($row['role'] == "admin" ? "selected" : '' ) ?>>ADMIN</option>
            <option value="viewer" <?= ($row['role'] == "viewer" ? "selected" : '' ) ?>>PENGUNJUNG</option>
           </select>
            <br>    
             <div class="btn-group">
            <button type="submit">SIMPAN PERUBAHAN</button>
            <a href="role.php" class="btn-back">KEMBALI</a>
        </div>
            
        </form>
    </div>
    </div>
</body>
</html>
