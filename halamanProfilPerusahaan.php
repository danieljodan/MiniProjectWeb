<?php
require_once "koneksi.php";

if (isset($_GET['id'])) {
    $id_perusahaan = intval($_GET['id']);

    // Ambil data detail perusahaan
    $sql_perusahaan = "SELECT * FROM perusahaan WHERE id_perusahaan = $id_perusahaan";

    $result_perusahaan = mysqli_query($conn, $sql_perusahaan);
    $profil = mysqli_fetch_assoc($result_perusahaan);

    // Ambil data semua lowongan yang ada di perusahaan dan tampilkan total pelamarnya
    $sql_semua_lowongan = "SELECT pekerjaan.id_pekerjaan, pekerjaan.judul_pekerjaan, pekerjaan.deskripsi, COUNT(lamaran.id_lamaran) AS total_pelamar FROM pekerjaan LEFT JOIN lamaran ON pekerjaan.id_pekerjaan = lamaran.id_pekerjaan WHERE pekerjaan.id_perusahaan = $id_perusahaan GROUP BY pekerjaan.id_pekerjaan";

    $result_semua_lowongan = mysqli_query($conn, $sql_semua_lowongan);
} else {
    echo "Perusahaan tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $profil['nama_perusahaan']; ?></title>
    <link rel="stylesheet" href="stylePerusahaan.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <a href ="halamanUtama.php"><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></a>
        <h1>
            <a href="halamanUtama.php">SahabatKarier</a>
        </h1>
        <nav>
            <a id="Dashboard" href="halamanDashboard.php" style="display: inline;"><i class='bx bx-home-alt-2'>&ThickSpace;</i>Dashboard</a>
            <a id="Logout" href="logout.php" style="display: inline;"><i class='bx bx-log-out'>&ThickSpace;</i>Logout</a>
            <!-- <a id="Register" href="halamanRegister.php"><i class='bx bxs-pencil'></i>&ThickSpace;</i>Register</a>
            <a id="Login" href="halamanLogin.php"><i class='bx bxs-user-circle' >&ThickSpace;</i>Login</a> -->
        </nav>
    </header>

    <main>
        <div class="shadow">
            <section class="ProfilPerusahaan">
                <img src=<?php echo $profil['logo_path']; ?> alt="PTEshaParama" width="100px">
                <div>
                    <h2>
                        <i class='bx bx-check-shield' style="color: green">&ThickSpace; &ThickSpace;</i><?php echo $profil['nama_perusahaan']; ?>
                    <h2>
                    <p class="alamat-perusahaan">
                        <i class='bx bxs-map-pin'>&ThickSpace; &ThickSpace;</i><?php echo $profil['alamat']; ?>
                    </p>
                </div>
                
            </section>
        </div>
        <div class="daftar-lowongan">
            <h2>Lowongan dari perusahaan ini:</h2>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result_semua_lowongan)) { 
                    ?>
                        <li class="lowongan-card">
                            <a href="halamanDetail.php">
                                <h3 class="judul-lowongan"><?= htmlspecialchars($row['judul_pekerjaan']) ?></h3>
                                <p class="deskripsi-lowongan"><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
                                <span class="total-pelamar">Total pelamar: <?= (int)$row['total_pelamar'] ?></span>
                            </a>    
                        </li>
                    <?php } 
                ?>
            </ul>
        </div>

    </main>
    
    <footer>
        <div class="FooterContainer">
            <div class="FooterLogo">
                <h1>Sahabat Karier</h1>
            </div>
            <div class="FooterDescription">
                <p>Sahabat Karier adalah platform pencarian kerja yang didedikasikan untuk membantu para pencari kerja di Indonesia menemukan peluang karier terbaik dengan lebih mudah dan efisien. Dengan konsep sebagai "sahabat" dalam perjalanan profesional, kami menyediakan berbagai lowongan kerja dari perusahaan ternama, serta fitur pencocokan kerja yang disesuaikan dengan keterampilan dan minat pengguna.</p>
            </div>
            <div class="FooterRegulator">
                <p>Terdaftar dan Diawasi Oleh</p>
                <div class="LembagaPengawas">
                    <a href="https://www.komdigi.go.id/"><img id="Komidigi" src="image/Komidigi.png" alt="Komidigi"></a>
                    <a href="https://kemnaker.go.id/"><img id="KementerianKetenagakerjaan" src="image/KementerianKetenagakerjaan.png" alt="KementerianKetenagakerjaan"></a>
                </div>
            </div>
        </div>
        <div class="FooterBottom">
            <p>&copy; 2025 Sahabat Karier. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>