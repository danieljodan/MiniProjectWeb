<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: halamanLogin.php');
    exit;
}

if (!isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    $user_sql = "SELECT email FROM users WHERE id_users = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $_SESSION['user_id']);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
        $_SESSION['email'] = $user_data['email'];
    }
}

$form_submitted = false;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_submitted = true;

    
    $id_pekerjaan = (int)$_POST['id_pekerjaan'];
    $id_users = $_SESSION['user_id'];
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $email = trim($_POST['email']);
    $no_telepon = trim($_POST['no_telepon']);

    if (empty($nama_lengkap) || empty($tanggal_lahir) || empty($email) || empty($no_telepon)) {
        echo "<script>alert('Semua field wajib harus diisi!'); window.history.back();</script>";
        exit;
    }

    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('File CV wajib diupload!'); window.history.back();</script>";
        exit;
    }

    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    function uploadFile($file, $field_name, $upload_dir) {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return ''; // Optional file not uploaded
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error uploading $field_name file");
        }
        
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception("$field_name file size must be less than 5MB");
        }
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
    
            $allowed_extensions = [];
        switch ($field_name) {
            case 'CV':
                $allowed_extensions = ['pdf', 'docx'];
                break;
            case 'Portofolio':
                $allowed_extensions = ['pdf'];
                break;
            case 'Surat Lamaran':
                $allowed_extensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png'];
                break;
        }
        
        if (!in_array($file_extension, $allowed_extensions)) {
            throw new Exception("$field_name file type not allowed. Allowed: " . implode(', ', $allowed_extensions));
        }
        
        $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $target_path = $upload_dir . $new_filename;
        
        if (!move_uploaded_file($file['tmp_name'], $target_path)) {
            throw new Exception("Failed to upload $field_name file");
        }
        
        return $target_path;
    }

    try {
        $cv_path = uploadFile($_FILES['cv'], 'CV', $upload_dir);
        $portofolio_path = uploadFile($_FILES['portofolio'], 'Portofolio', $upload_dir);
        $lamaran_path = uploadFile($_FILES['surat_lamaran'], 'Surat Lamaran', $upload_dir);
        
        $sql = "INSERT INTO lamaran (id_pekerjaan, id_users, nama_lengkap, tanggal_lahir, email, no_telepon, cv_path, portofolio_path, lamaran_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssssss", $id_pekerjaan, $id_users, $nama_lengkap, $tanggal_lahir, $email, $no_telepon, $cv_path, $portofolio_path, $lamaran_path);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            throw new Exception("Database error: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        if (isset($cv_path) && file_exists($cv_path)) {
            unlink($cv_path);
        }
        if (isset($portofolio_path) && file_exists($portofolio_path)) {
            unlink($portofolio_path);
        }
        if (isset($lamaran_path) && file_exists($lamaran_path)) {
            unlink($lamaran_path);
        }
        
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="styleSubmit.css">
    <title>Halaman Pengajuan</title>
</head>

<body>
    <header>
        <a href="halamanUtama.php"><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></a>
        <h1>
            <a href="halamanUtama.php">SahabatKarier</a>
        </h1>
        <nav>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <?php 
                    $username = explode('@', $_SESSION['email'])[0];
                ?>
                <span style="color: white; margin-right: 1em;">Selamat datang, <?php echo htmlspecialchars($username); ?></span>
                <a id="Logout" href="logout.php"><i class='bx bx-log-out'></i>&ThickSpace;Logout</a>
            <?php else: ?>
                <a id="Register" href="halamanRegister.php"><i class='bx bxs-pencil'></i>&ThickSpace;</i>Register</a>
                <a id="Login" href="halamanLogin.php"><i class='bx bxs-user-circle'>&ThickSpace;</i>Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php if ($form_submitted && $success): ?>
        <section class="feedback">
            <p>
            <div id="yay">
                Yay!
            </div>
            Lamaran kamu berhasil terkirim.
            <br>
            <br>Terima kasih sudah menggunakan Sahabat Karier.
            <br>Kami akan segera menghubungi kamu untuk proses selanjutnya.
            </p>
            <button class="kembali">
                <a href="halamanUtama.php">Kembali</a>
            </button>
        </section>
        <?php else: ?>
        <section class="feedback">
            <p>
            <div id="yay">
                Oops!
            </div>
            Terjadi kesalahan dalam memproses lamaran Anda.
            <br>
            <br>Silakan coba lagi.
            </p>
            <button class="kembali">
                <a href="halamanUtama.php">Kembali</a>
            </button>
        </section>
        <?php endif; ?>
    </main>

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
</body>
</html>
