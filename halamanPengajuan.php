<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="stylePengajuan.css">
    <title>Halaman Pengajuan</title>
</head>

<!--------------------------------------------------------------- HEADER --------------------------------------------------------------->



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
        <div class="shadow">
            <section class="ProfilPerusahaan">
                <h5>Anda sedang melamar untuk:</h5>
                <img src="image/PTEsha.png" alt="PTEshaParama" width="100px">
                <h2>Network Security Engineer</h2>
                <p><i class='bx bx-check-shield' style="color: green">&ThickSpace; &ThickSpace;</i>PT Esha Parama
                    Teknologi</p>
            </section>
            <div class="OpsiDaftar">
                <div class="Lamaran">
                    <a href="halamanDetail.php">Lihat deskripsi pekerjaan</a>
                </div>
            </div>
        </div>


        <!--------------------------------------------------------------- FORMULIR --------------------------------------------------------------->
        <section class="container">
            <h2 class="form-lamaran">Formulir Lamaran</h2>
            <form action="submit.php" method="get" class="form">
                <div class="input-box">
                    <label>Nama Lengkap</label>
                    <input type="text" placeholder="Masukkan nama lengkap anda" required />
                </div>
                <div class="input-box">
                    <label>Tanggal Lahir</label>
                        <input type="date" min="1900-01-01" max="2025-03-26" required />
                </div>
                <div class="input-box">
                    <label>E-mail</label>
                        <input type="email" placeholder="Masukkan e-mail anda" required />
                </div>
                <div class="input-box">
                    <label>Nomor HP</label>
                        <input type="tel" placeholder="Masukkan nomor HP anda" name="nomor_hp" pattern="[0-9]+"
                            required />
                </div>
                <div class="input-box">
                    <label>CV</label>
                        <input id="CV" type="file" accept=".pdf" required />
                        <p id="format_file">Format file yang diterima: pdf</p>
                </div>
                <div class="input-box">
                    <label>Portofolio <span id="Opsional"> (Opsional) </span></label>
                        <input id="Portofolio" type="file" accept=".pdf" />
                        <p id="format_file">Format file yang diterima: pdf</p>
                </div>
                <div class="input-box">
                    <label>Surat Lamaran<span id="Opsional"> (Opsional) </span></label>
                        <input id="Portofolio" type="file" />
                        <p id="format_file"></p>
                </div>

                <button>Kirim Lamaran</button>
            </form>
        </section>
    </main>
</body>

<!--------------------------------------------------------------- FOOTER --------------------------------------------------------------->
<footer>
    <div class="FooterContainer">
        <div class="FooterLogo">
            <h1>Sahabat Karier</h1>
        </div>
        <div class="FooterDescription">
            <p>Sahabat Karier adalah platform pencarian kerja yang didedikasikan untuk membantu para pencari kerja di
                Indonesia menemukan peluang karier terbaik dengan lebih mudah dan efisien. Dengan konsep sebagai
                "sahabat" dalam perjalanan profesional, kami menyediakan berbagai lowongan kerja dari perusahaan
                ternama, serta fitur pencocokan kerja yang disesuaikan dengan keterampilan dan minat pengguna.</p>
        </div>
        <div class="FooterRegulator">
            <p>Terdaftar dan Diawasi Oleh</p>
            <div class="LembagaPengawas">
                <a href="https://www.komdigi.go.id/"><img id="Komidigi" src="image/Komidigi.png" alt="Komidigi"></a>
                <a href="https://kemnaker.go.id/"><img id="KementerianKetenagakerjaan"
                        src="image/KementerianKetenagakerjaan.png" alt="KementerianKetenagakerjaan"></a>
            </div>
        </div>
    </div>
    <div class="FooterBottom">
        <p>&copy; 2025 Sahabat Karier. All rights reserved.</p>
    </div>
</footer>

</html>