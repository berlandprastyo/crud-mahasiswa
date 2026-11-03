<?php 
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include_once __DIR__ . "/config/config.php";
define('APP_ACCESS', true);
if (!defined('APP_ACCESS')) {
    http_response_code(403);
    exit("Forbidden");
}
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("ID tidak valid!");
} 
$id = (int)$_GET['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM mahasiswa WHERE id=?");
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
if (!$row) {
     die("Data Mahasiswa Tidak Ada!");   
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = strtoupper(trim($_POST["nama"]));
    $angkatan = trim($_POST["angkatan"]);
    $email = trim($_POST["email"]);
if (!preg_match("/^[a-zA-Z '-]+$/", $nama)) {
    $pesan = "Nama hanya boleh mengandung huruf!";
}
elseif (!ctype_digit($angkatan) ||  
            (int)$angkatan < 2000 || 
            (int)$angkatan > 2030) {
        $pesan = "Tahun Tidak Valid!";
}
elseif ((!filter_var($email, FILTER_VALIDATE_EMAIL))) {
   $pesan = "Email Tidak Valid";
}
else {
  $stmt = mysqli_prepare(
    $conn, 
    "UPDATE mahasiswa SET nama = ?, angkatan = ?, email = ? 
    WHERE id = ?"
);
mysqli_stmt_bind_param($stmt,"sisi",$nama,$angkatan,$email,$id);
if (mysqli_stmt_execute($stmt)){
$_SESSION['pesan'] = "Berhasil Update Data!";
} else {
    $_SESSION['pesan'] = "Gagal Update Data!";
}
header("Location: update.php?id=" . $id );
exit;
}
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
        <label for="npm">NPM :</label>
        <input type="text" name="npm" readonly value="<?=htmlspecialchars($row['npm']); ?>">
        <br>
            <label for="nama">NAMA :</label>
            <input type="text" name="nama" value="<?=htmlspecialchars($row['nama']); ?>">
            <br>
            <label for="prodi">PRODI :</label>
            <input type="text" name="prodi" readonly value="<?=htmlspecialchars($row['prodi']); ?>">
            <br>
            <label for="angkatan">TAHUN MASUK :</label>
            <input type="number" name="angkatan" value="<?=htmlspecialchars($row['angkatan']); ?>">
            <br>
            <label for="email">EMAIL :</label>
            <input type="text" name="email" value="<?=htmlspecialchars($row['email']); ?>">
            <br>
             <div class="btn-group">
            <button type="submit">SIMPAN PERUBAHAN</button>
            <a href="index.php" class="btn-back">KEMBALI</a>
        </div>
            
        </form>
    </div>
    </div>
</body>
</html>
