<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Karier | Login Page</title>
    <link href="styleLogin.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="halamanRegister.js"></script>
</head>
<body>
    <!-- halaman navigasi -->
    <header>
        <p><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></p>
        <h1>
            <p>SahabatKarier</p>
        </h1>
        <nav>
            <a id="Register" href="halamanRegister.php"><i class='bx bxs-pencil'></i>&ThickSpace;</i>Register</a>
            <a id="Login" href="halamanLogin.php"><i class='bx bxs-user-circle' >&ThickSpace;</i>Login</a>
        </nav>
    </header>
    <main>
        <div class="boxshadow">
            <section class="login">
                <h2>Masuk</h2>
                <p>Masuk ke Sahabat Karier Untuk Melanjutkan</p>
                <!-- Form untuk login -->
                <form action="halamanLogin.php" method="post">
                    <p><label for="emailID">Alamat Email: </label></p>
                    <p><input type="email" name="email" id="emailID" maxlength="100" required></p>
                    <p><label for="passwordID">Kata Sandi: </label></p>
                    <p><input type="password" name="password" id="passwordID" maxlength="100" required></p>
                    <p><input type="checkbox" name="showPassword" id="showPasswordID" onclick="seePassword()"><label for="showPasswordID">Show Password</label></p>
                    <p><input type="submit" name="submit" value="Login"></p>
                    <p class="daftar">Belum punya akun?<a href="halamanRegister.php">Daftar Sekarang</a></p>

                    <p id="keteranganID"></p>
                </form>
            </section>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Sahabat Karier. All rights reserved.</p>
    </footer>
</body>
</html>


<?php
    require_once "koneksi.php";

    // Membuat session untuk memudahkan pemindahan data antar halaman
    session_start();
    
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (isset($_POST["submit"])) {
        // Mengambil data yang sesuai dengan username dan password
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $sql);        
        if (mysqli_num_rows($result) > 0) {
            // Ambil data user per baris
            $user = mysqli_fetch_assoc($result);

            //Simpan dalam session
            $_SESSION['user_id'] = $user['id_users'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            // Redirect berdasarkan role user
            if ($user['role'] === 'company') {
                echo "<script>
                alert('Login Berhasil! Selamat datang di Dashboard Perusahaan');
                window.location.href = 'halamanPengelolaanLowongan.php';
                </script>";
            } else {
                echo "<script>
                alert('Login Berhasil!');
                window.location.href = 'halamanUtama.php';
                </script>";
            }
            
        } else {
            echo "<script>document.getElementById('keteranganID').innerHTML = 'Email atau Kata Sandi Salah!';</script>";
        }
    }
?>