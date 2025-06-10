<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Karier | Login Page</title>
    <link href="styleLogin.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <a href ="halamanUtama.html"><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></a>
        <h1>
            <a href="halamanUtama.html">SahabatKarier</a>
        </h1>
        <nav>
            <a id="Register" href="halamanRegister.html"><i class='bx bxs-pencil'></i>&ThickSpace;</i>Register</a>
            <a id="Login" href="halamanLogin.html"><i class='bx bxs-user-circle' >&ThickSpace;</i>Login</a>
        </nav>
    </header>
    <main>
        <div class="boxshadow">
            <section class="login">
                <h2>Masuk</h2>
                <p>Masuk ke Sahabat Karier Untuk Melanjutkan</p>
                <form action="halamanUtama.html" method="get">
                    <p><label for="emailID">Alamat Email: </label></p>
                    <p><input type="email" name="email" id="emailID" maxlength="100" required></p>
                    <p><label for="passwordID">Kata Sandi: </label></p>
                    <p><input type="password" name="password" id="passwordID" maxlength="100" required></p>
                    <a href="#" class="forget">Lupa Password?</a>
                    <p><input type="submit" value="Login"></p>
                    <p class="daftar">Belum punya akun?<a href="halamanRegister.html">Daftar Sekarang</a></p>
                </form>
            </section>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Sahabat Karier. All rights reserved.</p>
    </footer>
</body>
</html>