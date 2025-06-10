<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: halamanLogin.php');
    exit;
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Karier | Detail Pekerjaan</title>
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
                <img src="image/PTEsha.png" alt="PTEshaParama" width="100px">
                <h2>Network Security Engineer</h2>
                <p><i class='bx bx-check-shield' style="color: green">&ThickSpace; &ThickSpace;</i>PT Esha Parama Teknologi</p>
                <p><i class='bx bxs-map-pin'>&ThickSpace; &ThickSpace;</i>PT Esha Parama Teknologi, Kasablanka Office Tower A 26 F, Jl Casablanca Raya Kav.88, Jakarta, Jakarta Selatan 12870, Indonesia</p>
                <p><i class='bx bx-dollar'>&ThickSpace; &ThickSpace;</i>Rp 6.000.000 - 7.500.000 / Bulan</p>
                <p><i class='bx bx-buildings'>&ThickSpace; &ThickSpace;</i>Teknologi Informasi</p>
                <p><i class='bx bx-time'>&ThickSpace; &ThickSpace;</i>Full Time</p>
                <p><i class='bx bx-calendar'>&ThickSpace; &ThickSpace;</i>Tayang sejak: 3 Februari 2025</p>
            </section>
            <div class="OpsiDaftar">
                <div class="Lamaran">
                    <a href="halamanPengajuan.php">Lamar Pekerjaan</a>
                </div>
                <div class="Share">
                    <a href="#"><i class='bx bxs-share-alt'>&ThickSpace;</i>Share</a>
                </div>
            </div>
        </div>
        <section class="DeskripsiPekerjaan">
            <h3>Deskripsi Pekerjaan</h3>
            <ul>
                <li>Monitor customer network infrastructure</li>
                <li>Identify issues and errors before or as they occur</li>
                <li>Ensure normal day-to-day operations</li>
                <li>Report customer infrastructure conditions to Managers and customers.</li>
                <li>Collaborate with multi-functional teams to implement security controls and improve security posture</li>
                <li>Collect and analyze feedback from customer SOPs for periodic improvement</li>
                <li>Monitor and manage various firewalls</li>
                <li>Monitor firewall logs and respond to security incidents</li>
                <li>Remediate firewall vulnerabilities</li>
                <li>Implement firewall security policies and rules</li>
                <li>Provide support and guidance for secure endpoint use</li>
                <li>Collaborate with department heads and subject matter experts to gather information and insights for SOP development</li>
                <li>Responsible for SLAs provided by customers and other related tasks</li>
            </ul>
            <h3>Syarat dan Kualifikasi</h3>
            <ul>
                <li>Experience in implementing Fortinet, Palo Alto, CheckPoint, Juniper, or Huawei firewalls</li>
                <li>Understand the concept and operation of NGFW, IDS, IPS, Anti-DDoS, Sandboxing, VPN, and Security Management.</li>
                <li>Must have Associate level certification in NGFW, such as (PCNSA/FCA/FCP/HCIA Security/JNCIA Security/CCSA) are a plus.</li>
                <li>Excellent communication skills and ability to work well in a team</li>
                <li>Strong analytical and problem-solving skills with attention to detail</li>
                <li>Willing to be placed at the client's location in the Jakarta area with a shifting schedule</li>
                <li>Willing to be contracted for the duration of the project.</li>
                <li>Experience: minimum 2 (two) years of experience as a Network and Security Engineer.</li>
                <li>Bachelor's or Master's degree in Computer Science, Information Technology, or related field</li>
            </ul>
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
                    <li class="job-card">
                        <a href="halamanDetailAside.php">
                            <div>
                                <img src="image/Logo_TechTiera.jpg" alt="Esha Parama Technology">
                            </div>
                            <div>
                                <h3>Cyber Security Consultant</h3>
                                <h4 class="perusahaan-loker">PT TechTiera Services Indonesia</h3>
                                <p class="lokasi-loker">Jakarta Selatan, DKI Jakarta</p>
                                <div class="info-pekerjaan">
                                    <p class="badge badge-kategori">Teknologi Informasi</p>
                                    <p class="badge badge-jenis">Penuh Waktu</p>
                                </div> 
                            </div>
                        </a>
                    </li>
                    <li class="job-card">
                        <a href="halamanDetailAside.php">
                            <div>
                                <img src="image/GaneshaOperationAbianbase.webp" alt="GaneshaOperation">
                            </div>
                            <div>
                                <h3>Pengajar Informatika</h3>
                                <h4 class="perusahaan-loker">Ganesha Operation Abianbase</h3>
                                <p class="lokasi-loker">Mengwi, Bali</p>
                                <div class="info-pekerjaan">
                                    <p class="badge badge-kategori">Pendidikan</p>
                                    <p class="badge badge-jenis">Paruh Waktu</p>
                                </div> 
                            </div>
                        </a>
                    </li>
                    <li class="job-card">
                        <a href="halamanDetailAside.php">
                            <div>
                                <img src="image/YayasanEnamPeduliPendidikan.webp" alt="YayasanEnamPeduliPendidikan">
                            </div>
                            <div>
                                <h3>Dosen Perguruan Tinggi</h3>
                                <h4 class="perusahaan-loker">Yayasan Enam Peduli Pendidikan</h3>
                                <p class="lokasi-loker">Cibitung, Jawa Barat</p>
                                <div class="info-pekerjaan">
                                    <p class="badge badge-kategori">Pendidikan</p>
                                    <p class="badge badge-jenis">Penuh Waktu</p>
                                </div> 
                            </div>
                        </a>
                    </li>
                </ul>
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

