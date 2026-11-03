<?php 
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
};
include_once __DIR__ . "/config/config.php";
define('APP_ACCESS',true);
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['username'];
    $oldPassword = $_POST['oldPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $repeatPassword = $_POST['repeatPassword'] ?? '';
  if ($oldPassword == '' || $newPassword == '' || $repeatPassword == '') {
        $pesan = "Semua Form Wajib Diisi!";
  } else {
    $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
      if (!$row || !password_verify($oldPassword, $row['password'])) {
            $pesan = "Password Yang Anda Masukkan Salah!";
        } elseif ($newPassword != $repeatPassword) {
            $pesan = "Ulangi Password Dengan Benar!";
        } elseif ($oldPassword == $newPassword) {
            $pesan = "Password Baru Tidak Boleh Sama Dengan Password Lama!";
        } else {
            $hashPassword = password_hash($newPassword,PASSWORD_DEFAULT);
            $update = mysqli_prepare($conn,"UPDATE users SET password=? WHERE username=?");
            mysqli_stmt_bind_param($update,"ss",$hashPassword,$username);
           if (mysqli_stmt_execute($update)) {
            $_SESSION['pesan'] = "Password Berhasil Diubah!";
            } else {
            $_SESSION['pesan'] = "Gagal Mengubah Password!";
            }

mysqli_stmt_close($update);
            header("Location: ganti.php");
            exit;

        }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAMBAH DATA MAHASISWA</title>
      <link rel="icon" type="image/png" href="<?=htmlspecialchars($logo); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include  __DIR__ . "/include/header.php";?>

    <div class="container-add">
<div class="form-add">
    <?php if ($pesan != "") { ?>
    <div class="alert">
        <?= htmlspecialchars($pesan); ?>
    </div>
<?php } ?>
    <form action="" method="POST">
        <label for="npm">PASSWORD LAMA</label>
        <input type="password" name="oldPassword" required placeholder="Masukkan Password Lama">
        <br>
        <label for="nama">PASSWORD BARU</label>
        <input type="password" name="newPassword" required placeholder="Masukkan Password Baru">
        <br>
        <label for="prodi">ULANGI PASSWORD BARU </label>
        <input type="password" name="repeatPassword" required placeholder="Ulangi Password Baru">
        <br>
        <div class="btn-group">
            <button type="submit">GANTI</button>
            <a href="index.php" class="btn-back">KEMBALI</a>
        </div>
    </form>
</div>
</div>
</body>
</html>