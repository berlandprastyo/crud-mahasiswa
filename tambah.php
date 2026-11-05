<?php 
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
  if ($_SESSION['role'] !== "admin") {
        header("Location: index.php");
        exit;
    }
include_once __DIR__ . "/config/config.php";
define('APP_ACCESS', true);
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npm = trim($_POST["npm"]);
    $nama = strtoupper(trim($_POST["nama"]));
    $prodi = strtoupper(trim($_POST["prodi"]));
    $angkatan = $_POST["angkatan"];
    $email = trim($_POST["email"]);
    if (!ctype_digit($npm)) {
        $pesan = ("NPM Hanya Bisa Angka!");
    }
    elseif (strlen($npm) != 10) {
        $pesan = ("NPM Hanya Bisa 10 Karakter");
    }
    elseif (!preg_match("/^[a-zA-Z '-]+$/",$nama)) {
    $pesan = "Nama hanya boleh mengandung huruf!";
    }
    elseif (!ctype_digit($angkatan) ||  
            (int)$angkatan < 2000 || 
            (int)$angkatan > 2050) {
        $pesan = "Tahun Tidak Valid!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $pesan = ("Email Tidak Valid!");
    } 
    else {
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id FROM mahasiswa WHERE npm=?"
    );
    mysqli_stmt_bind_param($stmt,'s',$npm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $pesan = ("NPM Sudah Terdaftar!");
    }
    else {
        $stmt = mysqli_prepare(
        $conn,
        "SELECT id FROM mahasiswa WHERE email=?"
    );
    mysqli_stmt_bind_param($stmt,'s',$email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
     if (mysqli_num_rows($result) > 0) {
        $pesan = ("Email Sudah Terdaftar!");
    }
                
     else{
      $stmt = mysqli_prepare(
    $conn,  "INSERT INTO mahasiswa(npm, nama, prodi, angkatan, email) 
    VALUES (?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param($stmt,"sssis",$npm,$nama,$prodi,$angkatan,$email);
if (mysqli_stmt_execute($stmt)) {
$_SESSION['pesan'] = "Berhasil Tambah Mahasiswa!";
} else {
$_SESSION['pesan'] = "Gagal Tambah Mahasiswa!";    
}
header("Location: tambah.php");
exit;
}
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
        <label for="npm">NPM</label>
        <input type="text" name="npm" required placeholder="15126xxxxx">
        <br>
        <label for="nama">NAMA</label>
        <input type="text" name="nama" required placeholder="Masukkan Nama Mahasiswa">
        <br>
        <label for="prodi">PROGRAM STUDI</label>
        <input type="text" name="prodi" required placeholder="Masukkan Program Studi">
        <br>
        <label for="angkatan">TAHUN MASUK</label>
        <input type="number" name="angkatan" min="2010" max="2099" required placeholder="Masukkan Tahun Masuk">
        <br>
        <label for="email">EMAIL</label>
        <input type="email" name="email" required placeholder="Masukkan Email Mahasiswa">
        <br>
        <div class="btn-group">
            <button type="submit">TAMBAH DATA</button>
            <a href="index.php" class="btn-back">KEMBALI</a>
        </div>
    </form>
</div>
</div>
</body>
</html>