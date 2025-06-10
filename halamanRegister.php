<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Karier | Login Page</title>
    <link href="styleRegister.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="halamanRegister.js"></script>
</head>
<body>
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
            <section class="register">
                <h2>Daftar</h2>
                <p>Silahkan Daftar Untuk Melanjutkan ke SahabatKarier</p>
                <form action="halamanRegister.php" method="post">
                    <p><label for="emailID">Alamat Email: </label></p>
                    <p><input type="email" name="email" id="emailID" maxlength="100" required></p>
                    <p><label for="passwordID">Kata Sandi: </label></p>
                    <p><input type="password" name="password" id="passwordID" maxlength="100" required></p>
                    <p><label for="passwordKonfirmID">Konfirmasi Kata Sandi: </label></p>
                    <p><input type="password" name="passwordKonfirm" id="passwordKonfirmID" maxlength="100" required></p>
                    <p><input type="checkbox" name="showPassword" id="showPasswordID" onclick="seePassword()"><label for="showPasswordID">Show Password</label></p>
                    
                    
                    <section class="statusLbl">
                    <p><label>Apakah Anda Perusahaan atau Calon Karyawan: </label></p>
                    <p><input type="radio" name="role" id="rolePID" value="company"><label for="statusPID">Perusahaan</label></p>
                    <p><input type="radio" name="role" id="roleKID" value="jobseeker"><label for="statusKID">Calon Karyawan</label></p>
                    </section>

                    <p><input type="submit" name="submit" value="Daftar" onclick="return konfirmasiEmailPassword()"></p>
                    <p class="login">Sudah punya akun?<a href="halamanLogin.php">Silahkan login!</a></p>

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
    // halamanRegister.php
    require_once "koneksi.php";
    
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $passwordKonfirm = $_POST["passwordKonfirm"] ?? "";
    $role = $_POST["role"] ?? "";

    if (isset($_POST["submit"])) {
        if ($email !== "" && $password != "" && $passwordKonfirm != "" && $role != "") {
        $sqlCek = "SELECT id_users FROM users WHERE email = '$email'";
        $cek = mysqli_query($conn,$sqlCek);
        if (mysqli_num_rows($cek) > 0) {
            // Email sudah ada
            echo "<script>
                    document.getElementById('keteranganID').innerHTML = 'Email Sudah Terdaftar. Silakan Gunakan Email Lain!';
                  </script>";
            exit;
        }

        $sql = "INSERT INTO users (email, password, role) VALUES ('$email', '$password', '$role')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>
            alert('Registrasi Berhasil! Silahkan Login!');
            window.location.href = 'halamanLogin.php';
            </script>";
        }
        }
    }
?>
