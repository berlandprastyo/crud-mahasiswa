<?php 
session_start();
include_once  __DIR__ . "/config/config.php";
$pesan = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt,"s",$username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($row && password_verify($password,$row['password'])) {
         $_SESSION['login'] = true;
         $_SESSION['username'] = $row['username'];
        header("Location: index.php");
        exit;
    }
    else{
        $pesan = ("Username Atau Password Salah!");
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

    <div class="container-add">
<div class="form-add">
    <?php if ($pesan != "") { ?>
    <div class="alert">
        <?= htmlspecialchars($pesan); ?>
    </div>
<?php } ?>
    <form action="" method="POST">
        <label for="nama">USERNAME</label>
        <input type="text" name="username" required placeholder="Masukkan Username">
        <br>
        <label for="prodi">PASSWORD</label>
        <input type="password" name="password" required placeholder="Masukkan Password">
        <br>
        <div class="btn-group">
            <button type="submit">LOGIN</button>
        </div>
    </form>
</div>
</div>
</body>
</html>