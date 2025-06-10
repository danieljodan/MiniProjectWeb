<?php
    require_once "koneksi.php";
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: halamanLogin.php');
        exit;
    }

    // Ambil data dari URL dan cek jika ID tidak ditemukan
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id']; 
    } else {
        null;
    }

    //Ambil data dari DB
    $sql = "SELECT p.*, c.nama_perusahaan, c.logo_path, c.alamat FROM pekerjaan p INNER JOIN perusahaan c ON p.id_perusahaan = c.id_perusahaan
            WHERE p.id_pekerjaan = $id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 0) {
        echo "Lowongan tidak ditemukan";
        exit;
    }
    $job = mysqli_fetch_assoc($result);

    // Menampilkan detail pekerjaan

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Karier | <?php echo $job['judul_pekerjaan']; ?></title>
    <link href="styleDetailPekerjaan.css" rel="stylesheet">
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
        <div class="shadow">
            <section class="ProfilPerusahaan">
                <img src=<?php echo $job['logo_path']; ?> alt="PTEshaParama" width="100px">
                <h2><?php echo $job['judul_pekerjaan']; ?></h2>
                <p><i class='bx bx-check-shield' style="color: green">&ThickSpace; &ThickSpace;</i><?php echo $job['nama_perusahaan']; ?></p>
                <p><i class='bx bxs-map-pin'>&ThickSpace; &ThickSpace;</i><?php echo $job['alamat']; ?></p>
                <p><i class='bx bx-dollar'>&ThickSpace; &ThickSpace;</i><?php echo number_format($job['gaji_minimum'], 0, ',', '.'); ?> – <?php echo number_format($job['gaji_maksimum'], 0, ',', '.'); ?> / Bulan</p>
                <p><i class='bx bx-buildings'>&ThickSpace; &ThickSpace;</i><?php echo $job['kategori']; ?></p>
                <p><i class='bx bx-time'>&ThickSpace; &ThickSpace;</i><?php echo $job['jenis_pekerjaan']; ?></p>
                <p><i class='bx bx-calendar'>&ThickSpace; &ThickSpace;</i>Tayang sejak: <?php echo date('d F Y', strtotime($job['tanggal_uploud'])); ?></p>
            </section>
            <div class="OpsiDaftar">
                <div class="Lamaran">
                    <a href="halamanPengajuan.php?id=<?php echo $job['id_pekerjaan']; ?>">Lamar Pekerjaan</a>
                </div>
            </div>
        </div>
        <section class="DeskripsiPekerjaan">
            <h3>Deskripsi Pekerjaan</h3>
            <p>
                <ul>
                <?php
                // pecah string deskripsi jadi array kalimat
                    $sentences = explode('.', $job['deskripsi']);
                    foreach ($sentences as $sentence) {
                        $s = trim($sentence);
                        if ($s !== '') {
                            echo '<li>' . $s . '</li>';
                        }
                    }
                ?>
                </ul>    
            </p>
            <h3>Syarat dan Kualifikasi</h3>
            <p>
                <ul>
                <?php
                // pecah string Syarat dan Kualifikasi jadi array kalimat
                    $sentences = explode('.', $job['deskripsi']);
                    foreach ($sentences as $sentence) {
                        $s = trim($sentence);
                        if ($s !== '') {
                            echo '<li>' . $s . '</li>';
                        }
                    }
                ?>
                </ul>  
            </p>
        </section>
        <section class="Tips">
            <h4>Tips Aman Cari Kerja!</h4>
            <p>Pemberi kerja yang benar tidak akan meminta akun Telegram, top-ups atau pembayaran dalam bentuk apapun. Jangan berikan kontak pribadi, informasi bank, maupun kartu kredit kamu.</p>
        </section> 
    </main>
    <aside>
        <h3>Lowongan Lainnya</h3>
        <div class="LowonganLainnya">
            <section class="job-list">
                <ul>
                    <?php
                        $sqlAside = "SELECT p.id_pekerjaan, p.judul_pekerjaan, c.nama_perusahaan, c.alamat, c.logo_path, p.kategori, p.jenis_pekerjaan
                        FROM pekerjaan p INNER JOIN perusahaan c ON p.id_perusahaan = c.id_perusahaan
                        WHERE p.id_pekerjaan <> {$id} AND p.kategori = '{$job['kategori']}' ORDER BY RAND() LIMIT 3";
                        $rsAside = mysqli_query($conn, $sqlAside);
                        while ($o = mysqli_fetch_assoc($rsAside)):
                    ?>
                    <li class="job-card">
                        <a href="halamanDetail.php?id=<?php echo $o['id_pekerjaan']; ?>">
                            <div>
                                <img src="<?php echo $o['logo_path']; ?>" alt="<?php echo $o['nama_perusahaan']; ?>">
                            </div>
                            <div>
                                <h3><?php echo $o['judul_pekerjaan']; ?></h3>
                                <h4 class="perusahaan-loker"><?php echo $o['nama_perusahaan']; ?></h3>
                                <p class="lokasi-loker"><?php echo $o['alamat']; ?></p>
                                <div class="info-pekerjaan">
                                    <p class="badge badge-kategori"><?php echo $o['kategori']; ?></p>
                                    <p class="badge badge-jenis"><?php echo $o['jenis_pekerjaan']; ?></p>
                                </div> 
                            </div>
                        </a>
                    </li>
                    <?php endwhile; ?>
            </section>
        </div>
    </aside>
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

