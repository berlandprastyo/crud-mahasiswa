<?php 
session_start();
include_once __DIR__ . "/config/config.php";
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $username = trim($_POST['username']);
   $password = $_POST['password'];
   $repeatPassword = $_POST['repeatPassword'];
   if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/",$username)) {
   $_SESSION['pesan'] = "Username Yang Diperbolehkan 5-20 karakter dan Tanpa Spasi";
   }
   elseif (strlen($password) < 8) {
    $_SESSION['pesan'] = "Password minimal 8 karakter!";
    }
   elseif ($password != $repeatPassword) {
    $_SESSION['pesan'] = "Masukkan Password Dengan Benar!";
    }
   else {
    $stmt = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE username=?"
    );
    mysqli_stmt_bind_param($stmt,'s',$username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['pesan'] = "Username Sudah Terdaftar!";
    }
    else {
        $hashPassword = password_hash($password,PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn,
        "INSERT into users(username, password) VALUES (?,?)"
        );
        mysqli_stmt_bind_param($stmt,"ss",$username,$hashPassword);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan'] = "Berhasil Membuat Akun, Silahkan Login Kembali!";
        }
        else {
            $_SESSION['pesan'] = "Gagal Membuat Akun, Silahkan Coba Lagi!";
        }
    }
}
header("Location: login.php");
exit;
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
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <header class="login-header">
    <img src="<?=htmlspecialchars($logo); ?>" alt="">
    <div>
        <h1><?=htmlspecialchars($header);?></h1>
        <p>SISTEM PENGELOLAAN DATA MAHASISWA</p>
    </div>
</header>

    <div class="container-add">
<div class="form-add">
    <?php if ($pesan != "") { ?>
    <div class="alert">
        <?= htmlspecialchars($pesan); ?>
    </div>
<?php } ?>
    <form action="" method="POST">
        <label for="username">USERNAME</label>
        <input type="text" name="username" required placeholder="Masukkan Username">
        <br>
        <label for="password">PASSWORD</label>
        <div class="password">
            <input type="password" name="password" id="password" required placeholder="Masukkan Password">
            <button type="button" id="toggle">
                <i class="fa-solid fa-eye-slash"></i>
            </button>
        </div>
        <br>
        <label for="repeatPassword">ULANGI PASSWORD</label>
        <input type="password" name="repeatPassword" required placeholder="Ulangi Password">
        <br>
        
        <div class="btn-group">
            <button type="submit">BUAT AKUN</button>
            <a href="index.php" class="btn-back">KEMBALI</a>
        </div>
    </form>
</div>
</div>
<script src="/assets/script.js"></script>
</body>
</html>