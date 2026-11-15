<?php 
session_start();
include_once  __DIR__ . "/config/config.php";
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $_SESSION['random1'] = rand(1, 10);
    $_SESSION['random2'] = rand(1, 10);
    $_SESSION['hasil'] = $_SESSION['random1'] + $_SESSION['random2'];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $hasilRandom = (int)$_POST['random'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt,"s",$username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($hasilRandom != $_SESSION['hasil']) {
        $_SESSION['pesan'] = "Jawaban Anda Salah!";
        unset($_SESSION['hasil'],$_SESSION['random1'],$_SESSION['random2']);
        header("Location: login.php");
        exit;
    }
    elseif ($row && password_verify($password,$row['password'])) {
         $_SESSION['login'] = true;
         $_SESSION['username'] = $row['username'];
         $_SESSION['role'] = $row['role'];
         $_SESSION['id'] = $row['id'];
        unset($_SESSION['hasil'],$_SESSION['random1'],$_SESSION['random2']);
        header("Location: index.php");
        exit;
    }
    else{
    $_SESSION['pesan'] = "Username Atau Password Salah!";
    unset($_SESSION['hasil'],$_SESSION['random1'],$_SESSION['random2']);
    header("Location: login.php");
    exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="<?=htmlspecialchars($logo); ?>">
</head>
<body>
   <header class="login-header">
    <img src="<?=htmlspecialchars($logo); ?>" alt="">
    <div>
        <h1><?=htmlspecialchars($header);?></h1>
        <p>SISTEM PENGELOLAAN DATA MAHASISWA</p>
    </div>
</header>
    <div class="container-login">
<div class="form-add">
    <?php if ($pesan != "") { ?>
    <div class="alert">
        <?= htmlspecialchars($pesan); ?>
    </div>
<?php } ?>
    <form action="" method="POST">
        <label for="nama"><b>USERNAME</b></label>
        <input type="text" name="username" required placeholder="Masukkan Username">
        <br>
        <label for="password"><b>PASSWORD</b></label>
        <div class="password">
            <input type="password" name="password" id="password" required placeholder="Masukkan Password">
            <button type="button" id="toggle">
                <i class="fa-solid fa-eye-slash"></i>
            </button>
        </div>
        <br>
        <label for="random"><b><?=$_SESSION['random1'] . " + " . $_SESSION['random2'] . " =" ?></b></label>
        <input type="number" name="random" required placeholder="Masukkan Jawaban"> 
        <p>Belum Memiliki Akun? <a href="registrasi.php">Buat Akun</a></p> 
        <div class="btn-group">
            <button type="submit">LOGIN</button>
        </div>
    </form>
</div>
</div>
<script src="/assets/script.js"></script>
</body>
</html>