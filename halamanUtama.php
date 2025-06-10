<?php
// koneksi ke database
include "koneksi.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: halamanLogin.php');
exit;
}

$keyword = $_GET['keyword'] ?? '';
$alamat = $_GET['alamat'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$jenis = $_GET['jenis'] ?? '';
$gaji_min = $_GET['gaji_minimum'] ?? '';
$gaji_max = $_GET['gaji_maksimum'] ?? '';

$sql = "SELECT pekerjaan.id_pekerjaan, pekerjaan.judul_pekerjaan, pekerjaan.kategori, pekerjaan.gaji_minimum, pekerjaan.gaji_maksimum, pekerjaan.jenis_pekerjaan, perusahaan.nama_perusahaan, perusahaan.logo_path, perusahaan.alamat FROM pekerjaan JOIN perusahaan ON pekerjaan.id_perusahaan = perusahaan.id_perusahaan WHERE pekerjaan.tanggal_deadline > CURDATE()";

// Filter pencarian
if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $sql .= " AND (pekerjaan.judul_pekerjaan LIKE '%$keyword%' OR perusahaan.nama_perusahaan LIKE '%$keyword%')";
}
if (!empty($alamat)) {
    $alamat = mysqli_real_escape_string($conn, $alamat);
    $sql .= " AND perusahaan.alamat LIKE '%$alamat%'";
}
if (!empty($kategori)) {
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $sql .= " AND pekerjaan.kategori = '$kategori'";
}
if (!empty($jenis)) {
    $jenis = mysqli_real_escape_string($conn, $jenis);
    $sql .= " AND pekerjaan.jenis_pekerjaan = '$jenis'";
}
if (!empty($gaji_min)) {
    $gaji_min = (int) $gaji_min;
    $sql .= " AND pekerjaan.gaji_minimum >= $gaji_min";
}
if (!empty($gaji_max)) {
    $gaji_max = (int) $gaji_max;
    $sql .= " AND pekerjaan.gaji_maksimum <= $gaji_max";
}

$sql .= " ORDER BY pekerjaan.tanggal_uploud DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SahabatKarier</title>
    <link rel="stylesheet" href="style.css">
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
        <article>
            <section class="search-container">
                <form action="" method="GET">
                    <div class="search-row">
                        <input type="text" name="keyword" placeholder="Cari nama pekerjaan atau perusahaan">
                        <input type="text" name="alamat" placeholder="Lokasi">
                        <button type="submit">Cari</button>
                    </div>

                    <div class="filter-row">
                        <select name="kategori">
                            <option value="" disabled selected>Kategori Pekerjaan</option>
                            <option value="">Keuangan</option>
                            <option value="">Pemasaran</option>
                            <option value="">Pendidikan</option>
                            <option value="">Teknologi Informasi</option>
                        </select>
                        <select name="jenis">
                            <option value="" disabled selected>Jenis Pekerjaan</option>
                            <option value="Full Time">Full-time</option>
                            <option value="Part Time">Part-time</option>
                            <option value="Remote">Remote</option>
                            <option value="Freelance">Freelance</option>
                        </select>
                        <select name="gaji_minimum" id="">
                            <option label="Gaji Rp 0" value="0">0</option>
                            <option value="2000000">2 juta</option>
                            <option value="4000000">4 juta</option>
                            <option value="6000000">6 juta</option>
                            <option value="8000000">8 juta</option>
                            <option value="10000000">10 juta</option>
                        </select>
                        <select name="gaji_maksimum" id="">
                            <option label="hingga Rp 10 juta"> 0</option>
                            <option value="2000000">2 juta</option>
                            <option value="4000000">4 juta</option>
                            <option value="6000000">6 juta</option>
                            <option value="8000000">8 juta</option>
                            <option value="10000000">10 juta</option>
                        </select>
                    </div>
                    
                </form>
            </section>

            <section class="job-list">
                <h2>Lowongan Pekerjaan</h2>
                <ul>
                    <?php
                        $count = mysqli_num_rows($result);
                        if ($count > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <li class="job-card">
                                <a href="halamanDetail.php?id=<?php echo $row['id_pekerjaan'] ?>">
                                    <div>
                                        <img src="<?= htmlspecialchars($row['logo_path']) ?>" alt="<?= htmlspecialchars($row['nama_perusahaan']) ?>">
                                    </div>
                                    <div>
                                        <h3 class="nama-pekerjaan"><?= htmlspecialchars($row['judul_pekerjaan']) ?></h3>
                                        <h4 class="perusahaan-loker"><?= htmlspecialchars($row['nama_perusahaan']) ?></h4>
                                        <p class="lokasi-loker"><?= htmlspecialchars($row['alamat']) ?></p>
                                        <div class="gaji">
                                            <span>Rp <?= number_format($row['gaji_minimum'], 0, ',', '.') ?> - <?= number_format($row['gaji_maksimum'], 0, ',', '.') ?></span>
                                        </div>
                                        <div class="info-pekerjaan">
                                            <span class="badge badge-kategori"><?= htmlspecialchars($row['kategori']) ?></span>
                                            <span class="badge badge-jenis"><?= htmlspecialchars($row['jenis_pekerjaan']) ?></span>
                                        </div> 
                                    </div>
                                </a>
                            </li>
                            <?php
                            }
                        } else {
                            echo '<li class="search-not-found" style="display:none;"></li>';
                        }
                        
                    ?>
                </ul>
                <!-- <div>
                    <a href="halamanDetail.php">
                        <span>Load More</span>
                    </a>
                </div> -->
            </section>

        </article>
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
                    <a href="https://www.komdigi.go.id/">
                        <img id="Komidigi" src="image/Komidigi.png" alt="Komidigi">
                    </a>
                    <a href="https://kemnaker.go.id/">
                        <img id="KementerianKetenagakerjaan" src="image/KementerianKetenagakerjaan.png" alt="KementerianKetenagakerjaan">
                    </a>
                </div>
            </div>
        </div>
        <div class="FooterBottom">
            <p>&copy; 2025 Sahabat Karier. All rights reserved.</p>
        </div>
    </footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pencarianKosong = document.querySelector('.search-not-found');
    if (pencarianKosong) {
        const pesan = document.createElement('p');
        pesan.textContent = "Hasil Pencarian Tidak ditemukan";
        pesan.style.color = "red";
        pesan.style.textAlign = "center";
        pesan.style.display = "block"
        document.querySelector('.job-list ul').appendChild(pesan);
    }
});
</script>
</body>
</html>