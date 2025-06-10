<?php
require_once "koneksi.php";

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$sql = "SELECT perusahaan.id_perusahaan, perusahaan.nama_perusahaan, perusahaan.logo_path, COUNT(pekerjaan.id_pekerjaan) as total_pekerjaan FROM perusahaan JOIN pekerjaan ON perusahaan.id_perusahaan = pekerjaan.id_perusahaan";

if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $sql .= " WHERE perusahaan.nama_perusahaan LIKE '%$keyword%'";
}

$sql .= " GROUP BY perusahaan.id_perusahaan";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href ="halamanUtama.php"><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></a>
        <h1>
            <a href="halamanUtama.php">SahabatKarier</a>
        </h1>
        <nav>
            <a id="Dashboard" href="halamanDashboard.php"><i class='bx bx-home-alt-2' > &ThickSpace;</i>Dashboard</a>
            <a id="Logout" href="logout.php"><i class='bx bx-log-out'></i>&ThickSpace;Logout</a>
            <!-- <a id="Register" href="halamanRegister.php"><i class='bx bxs-pencil'></i>&ThickSpace;</i>Register</a>
            <a id="Login" href="halamanLogin.php"><i class='bx bxs-user-circle' >&ThickSpace;</i>Login</a> -->
        </nav>
    </header>

    <main>
        <article>
            <section class="search-container">
                <form action="halamanDashboard.php" method="GET">
                    <div class="search-row">
                        <input type="text" name="keyword" placeholder="Cari perusahaan">
                        <button type="submit">Cari</button>
                    </div>
                </form>
            </section>
            <section class="daftar-perusahaan">
                <h2>Daftar Perusahaan</h2>
                <div class="company-container">
                    <?php
                        $index = 0;
                        $count = mysqli_num_rows($result);
                        if ($count > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <div class="company-card" data-index="<?= $index ?>">
                                        <a href="profilPerusahaan.php">
                                            <div class="profil-singkat">
                                                <img src="<?= htmlspecialchars($row['logo_path']) ?>" alt="<?= htmlspecialchars($row['nama_perusahaan']) ?>">
                                                <h4><?= htmlspecialchars($row['nama_perusahaan']) ?></h4>
                                            </div>
                                            <span class="badge-total-pekerjaan"><?= (int)$row['total_pekerjaan'] ?> pekerjaan</span>
                                        </a>
                                    </div>
                                <?php
                                $index++;
                            }
                        } else {
                            echo '<div class="search-not-found" style="display:none;"></div>';
                        }
                        
                    ?>
                </div>
                <div class="pagination-buttons">
                    <button id="prevBtn"><</button>
                    <button id="nextBtn">></button>
                </div>
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
    document.addEventListener("DOMContentLoaded", function () {
        // Pencarian Nama Perusahaan
        const pencarianKosong = document.querySelector('.search-not-found');

        // Carousel
        const cards = document.querySelectorAll(".company-card");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        const perPage = 5;
        let currentPage = 0;
        const totalPages = Math.ceil(cards.length / perPage);

        
        if (pencarianKosong) {
            const pesan = document.createElement('p');
            pesan.textContent = "Data Tidak Ditemukan";
            pesan.style.color = "red";
            pesan.style.textAlign = "center";
            pesan.style.display = "block"
            document.querySelector('.company-container').appendChild(pesan);
        }

        function showPage(page) {
            cards.forEach((card, i) => {
                card.style.display = (i >= page * perPage && i < (page + 1) * perPage) ? "block" : "none";
            });

            prevBtn.disabled = page === 0;
            nextBtn.disabled = page === totalPages - 1;
        }

        prevBtn.addEventListener("click", () => {
            if (currentPage > 0) {
                currentPage--;
                showPage(currentPage);
            }
        });

        nextBtn.addEventListener("click", () => {
            if (currentPage < totalPages - 1) {
                currentPage++;
                showPage(currentPage);
            }
        });

        showPage(currentPage); // tampilkan pertama kali
    });
</script>
</body>
</html>