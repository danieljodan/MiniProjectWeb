<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Karier | Login Page</title>
    <link href="styleRegister.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <a href ="halamanUtama.php"><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></a>
        <h1>
            <a href="halamanUtama.php">SahabatKarier</a>
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
                <form action="halamanUtama.php" method="get">
                    <p><label for="emailID">Alamat Email: </label></p>
                    <p><input type="email" name="email" id="emailID" maxlength="100" required></p>
                    <p><label for="passwordID">Kata Sandi: </label></p>
                    <p><input type="password" name="password" id="passwordID" maxlength="100" required></p>
                    <p><label for="passwordKonfirmID">Konfirmasi Kata Sandi: </label></p>
                    <p><input type="password" name="passwordKonfirm" id="passwordKonfirmID" maxlength="100" required></p>
                    
                    <section class="statusLbl">
                    <p><label>Apakah Anda Perusahaan atau Calon Karyawan: </label></p>
                    <p><input type="radio" name="status" id="statusPID" value="Perusahaan"><label for="statusPID">Perusahaan</label></p>
                    <p><input type="radio" name="status" id="statusKID" value="Calon Karyawan"><label for="statusKID">Calon Karyawan</label></p>
                    </section>

                    <p><input type="submit" value="Daftar"></p>
                    <p class="login">Sudah punya akun?<a href="halamanLogin.php">Silahkan login!</a></p>
                </form>
            </section>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Sahabat Karier. All rights reserved.</p>
    </footer>
</body>
</html>