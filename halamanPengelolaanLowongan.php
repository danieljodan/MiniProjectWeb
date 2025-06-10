<?php
session_start();
include 'koneksi.php';

// Check if user is logged in and is a company
if (!isset($_SESSION['user_id'])) {
    header('Location: halamanLogin.php');
    exit;
}

// Get user role
$user_sql = "SELECT email, role FROM users WHERE id_users = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $_SESSION['email'] = $user_data['email'];
    $_SESSION['role'] = $user_data['role'];
      if ($user_data['role'] !== 'company') {
        header('Location: halamanLogin.php');
        exit;
    }
} else {
    header('Location: halamanLogin.php');
    exit;
}

// Get company data
$company_sql = "SELECT * FROM perusahaan WHERE id_users = ?";
$company_stmt = $conn->prepare($company_sql);
$company_stmt->bind_param("i", $_SESSION['user_id']);
$company_stmt->execute();
$company_result = $company_stmt->get_result();

if ($company_result->num_rows > 0) {
    $company = $company_result->fetch_assoc();
    $company_id = $company['id_perusahaan'];
    $show_profile_form = false;
} else {
    // If company profile doesn't exist, show profile creation form
    $company = array(
        'id_perusahaan' => 0,
        'nama_perusahaan' => '',
        'logo_path' => 'image/default-company.png',
        'alamat' => ''
    );
    $company_id = 0;
    $show_profile_form = true;
}

// Handle form submissions
$message = '';
$message_type = '';
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Handle create company profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_profile'])) {
    $nama_perusahaan = trim($_POST['nama_perusahaan']);
    $alamat = trim($_POST['alamat']);
    $logo_path = 'image/default-company.png'; // Default logo
    
    if (!empty($nama_perusahaan) && !empty($alamat)) {
        $create_sql = "INSERT INTO perusahaan (id_users, nama_perusahaan, logo_path, alamat) VALUES (?, ?, ?, ?)";
        $create_stmt = $conn->prepare($create_sql);
        $create_stmt->bind_param("isss", $_SESSION['user_id'], $nama_perusahaan, $logo_path, $alamat);
        
        if ($create_stmt->execute()) {
            $company_id = $conn->insert_id;
            $company = array(
                'id_perusahaan' => $company_id,
                'nama_perusahaan' => $nama_perusahaan,
                'logo_path' => $logo_path,
                'alamat' => $alamat
            );
            $show_profile_form = false;
            $message = "Profil perusahaan berhasil dibuat!";
            $message_type = "success";
        } else {
            $message = "Gagal membuat profil perusahaan.";
            $message_type = "error";
        }
    } else {
        $message = "Nama perusahaan dan alamat wajib diisi.";
        $message_type = "error";
    }
}

// Handle delete job
if (isset($_GET['delete_job'])) {
    $job_id = (int)$_GET['delete_job'];
    
    // Check if job belongs to this company
    $check_sql = "SELECT id_pekerjaan FROM pekerjaan WHERE id_pekerjaan = ? AND id_perusahaan = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $job_id, $company_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Check if job has applicants
        $applicant_sql = "SELECT COUNT(*) as count FROM lamaran WHERE id_pekerjaan = ?";
        $applicant_stmt = $conn->prepare($applicant_sql);
        $applicant_stmt->bind_param("i", $job_id);
        $applicant_stmt->execute();
        $applicant_result = $applicant_stmt->get_result();
        $applicant_count = $applicant_result->fetch_assoc()['count'];
        
        if ($applicant_count > 0) {
            $message = "Tidak dapat menghapus lowongan yang sudah memiliki pelamar.";
            $message_type = "error";
        } else {
            $delete_sql = "DELETE FROM pekerjaan WHERE id_pekerjaan = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $job_id);
            
            if ($delete_stmt->execute()) {
                $message = "Lowongan berhasil dihapus.";
                $message_type = "success";
            } else {
                $message = "Gagal menghapus lowongan.";
                $message_type = "error";
            }
        }
    }
}

// Handle add job
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_job'])) {
    $judul_pekerjaan = trim($_POST['judul_pekerjaan']);
    $kategori = trim($_POST['kategori']);
    $gaji_minimum = (int)$_POST['gaji_minimum'];
    $gaji_maksimum = (int)$_POST['gaji_maksimum'];
    $jenis_pekerjaan = $_POST['jenis_pekerjaan'];
    $deskripsi = trim($_POST['deskripsi']);
    $syarat_kualifikasi = trim($_POST['syarat_kualifikasi']);
    $tanggal_deadline = $_POST['tanggal_deadline'];
    
    if (!empty($judul_pekerjaan) && !empty($kategori) && !empty($deskripsi) && !empty($syarat_kualifikasi)) {
        $add_sql = "INSERT INTO pekerjaan (id_perusahaan, judul_pekerjaan, kategori, gaji_minimum, gaji_maksimum, jenis_pekerjaan, deskripsi, syarat_kualifikasi, tanggal_deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $add_stmt = $conn->prepare($add_sql);
        $add_stmt->bind_param("issiissss", $company_id, $judul_pekerjaan, $kategori, $gaji_minimum, $gaji_maksimum, $jenis_pekerjaan, $deskripsi, $syarat_kualifikasi, $tanggal_deadline);
        
        if ($add_stmt->execute()) {
            $message = "Lowongan baru berhasil ditambahkan.";
            $message_type = "success";
        } else {
            $message = "Gagal menambahkan lowongan.";
            $message_type = "error";
        }
    } else {
        $message = "Semua field wajib harus diisi.";
        $message_type = "error";
    }
}

// Handle edit job
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_job'])) {
    $job_id = (int)$_POST['job_id'];
    $judul_pekerjaan = trim($_POST['judul_pekerjaan']);
    $kategori = trim($_POST['kategori']);
    $gaji_minimum = (int)$_POST['gaji_minimum'];
    $gaji_maksimum = (int)$_POST['gaji_maksimum'];
    $jenis_pekerjaan = $_POST['jenis_pekerjaan'];
    $deskripsi = trim($_POST['deskripsi']);
    $syarat_kualifikasi = trim($_POST['syarat_kualifikasi']);
    $tanggal_deadline = $_POST['tanggal_deadline'];
    
    // Check if job belongs to this company
    $check_sql = "SELECT id_pekerjaan FROM pekerjaan WHERE id_pekerjaan = ? AND id_perusahaan = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $job_id, $company_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0 && !empty($judul_pekerjaan) && !empty($kategori) && !empty($deskripsi) && !empty($syarat_kualifikasi)) {
        $edit_sql = "UPDATE pekerjaan SET judul_pekerjaan = ?, kategori = ?, gaji_minimum = ?, gaji_maksimum = ?, jenis_pekerjaan = ?, deskripsi = ?, syarat_kualifikasi = ?, tanggal_deadline = ? WHERE id_pekerjaan = ?";
        $edit_stmt = $conn->prepare($edit_sql);
        $edit_stmt->bind_param("ssiissssi", $judul_pekerjaan, $kategori, $gaji_minimum, $gaji_maksimum, $jenis_pekerjaan, $deskripsi, $syarat_kualifikasi, $tanggal_deadline, $job_id);
        
        if ($edit_stmt->execute()) {
            $message = "Lowongan berhasil diperbarui.";
            $message_type = "success";
        } else {
            $message = "Gagal memperbarui lowongan.";
            $message_type = "error";
        }
    } else {
        $message = "Data tidak valid atau tidak ada akses.";
        $message_type = "error";
    }
}

// Get dashboard statistics
if ($company_id > 0) {
    $stats_sql = "SELECT 
        COUNT(p.id_pekerjaan) as total_jobs,
        COUNT(l.id_lamaran) as total_applications
    FROM pekerjaan p 
    LEFT JOIN lamaran l ON p.id_pekerjaan = l.id_pekerjaan 
    WHERE p.id_perusahaan = ?";
    $stats_stmt = $conn->prepare($stats_sql);
    $stats_stmt->bind_param("i", $company_id);
    $stats_stmt->execute();
    $stats_result = $stats_stmt->get_result();
    $stats = $stats_result->fetch_assoc();
} else {
    $stats = array('total_jobs' => 0, 'total_applications' => 0);
}

// Get jobs with applicant counts
if ($company_id > 0) {
    $jobs_sql = "SELECT p.*, COUNT(l.id_lamaran) as applicant_count 
    FROM pekerjaan p 
    LEFT JOIN lamaran l ON p.id_pekerjaan = l.id_pekerjaan 
    WHERE p.id_perusahaan = ? 
    GROUP BY p.id_pekerjaan 
    ORDER BY p.tanggal_uploud DESC";
    $jobs_stmt = $conn->prepare($jobs_sql);
    $jobs_stmt->bind_param("i", $company_id);
    $jobs_stmt->execute();
    $jobs_result = $jobs_stmt->get_result();
} else {
    $jobs_result = null;
}

// Get specific job data for editing
$edit_job = null;
if (isset($_GET['edit_job'])) {
    $edit_job_id = (int)$_GET['edit_job'];
    $edit_sql = "SELECT * FROM pekerjaan WHERE id_pekerjaan = ? AND id_perusahaan = ?";
    $edit_stmt = $conn->prepare($edit_sql);
    $edit_stmt->bind_param("ii", $edit_job_id, $company_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    if ($edit_result->num_rows > 0) {
        $edit_job = $edit_result->fetch_assoc();
        $current_page = 'edit_job';
    }
}

// Get job detail with applicants
$job_detail = null;
$applicants = [];
if (isset($_GET['job_detail'])) {
    $job_detail_id = (int)$_GET['job_detail'];
    $detail_sql = "SELECT p.*, COUNT(l.id_lamaran) as applicant_count 
    FROM pekerjaan p 
    LEFT JOIN lamaran l ON p.id_pekerjaan = l.id_pekerjaan 
    WHERE p.id_pekerjaan = ? AND p.id_perusahaan = ?
    GROUP BY p.id_pekerjaan";
    $detail_stmt = $conn->prepare($detail_sql);
    $detail_stmt->bind_param("ii", $job_detail_id, $company_id);
    $detail_stmt->execute();
    $detail_result = $detail_stmt->get_result();
    
    if ($detail_result->num_rows > 0) {
        $job_detail = $detail_result->fetch_assoc();
        $current_page = 'job_detail';
        
        // Get applicants for this job
        $applicants_sql = "SELECT * FROM lamaran WHERE id_pekerjaan = ? ORDER BY tanggal_lamaran DESC";
        $applicants_stmt = $conn->prepare($applicants_sql);
        $applicants_stmt->bind_param("i", $job_detail_id);
        $applicants_stmt->execute();
        $applicants_result = $applicants_stmt->get_result();
        while ($row = $applicants_result->fetch_assoc()) {
            $applicants[] = $row;
        }
    }
}

// Get applicant detail
$applicant_detail = null;
if (isset($_GET['applicant_detail'])) {
    $applicant_id = (int)$_GET['applicant_detail'];
    $applicant_sql = "SELECT l.*, p.judul_pekerjaan 
    FROM lamaran l 
    JOIN pekerjaan p ON l.id_pekerjaan = p.id_pekerjaan 
    WHERE l.id_lamaran = ? AND p.id_perusahaan = ?";
    $applicant_stmt = $conn->prepare($applicant_sql);
    $applicant_stmt->bind_param("ii", $applicant_id, $company_id);
    $applicant_stmt->execute();
    $applicant_result = $applicant_stmt->get_result();
    
    if ($applicant_result->num_rows > 0) {
        $applicant_detail = $applicant_result->fetch_assoc();
        $current_page = 'applicant_detail';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perusahaan - SahabatKarier</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>    <style>
        /* Custom styles for company dashboard */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .nav-tabs {
            display: flex;
            background: white;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }

        .nav-tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            color: #666;
            border-right: 1px solid #e9ecef;
        }

        .nav-tab:last-child {
            border-right: none;
        }

        .nav-tab:first-child {
            border-radius: 10px 0 0 0;
        }

        .nav-tab:last-child {
            border-radius: 0 10px 0 0;
        }

        .nav-tab.active {
            background: #667eea;
            color: white;
        }

        .nav-tab:hover:not(.active) {
            background: #e9ecef;
        }

        .content {
            background: white;
            border-radius: 0 0 10px 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 500px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .btn {
            background: #667eea;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #5a6fd8;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .job-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }

        .job-card h3 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .job-card .job-meta {
            display: flex;
            gap: 1rem;
            margin: 0.5rem 0;
            font-size: 0.9rem;
            color: #666;
        }

        .job-card .job-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .applicant-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 3px solid #28a745;
        }

        .applicant-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }

        .file-links a {
            color: #667eea;
            text-decoration: none;
            margin-right: 1rem;
        }

        .file-links a:hover {
            text-decoration: underline;
        }

        .back-btn {
            background: #6c757d;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs {
                flex-direction: column;
            }
            
            .nav-tab:first-child {
                border-radius: 10px 10px 0 0;
            }
            
            .nav-tab:last-child {
                border-radius: 0 0 0 0;
            }
            
            .dashboard-container {
                padding: 1rem;
            }
        }

        .nav-tabs {
            display: flex;
            background: white;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }

        .nav-tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            color: #666;
            border-right: 1px solid #e9ecef;
        }

        .nav-tab:last-child {
            border-right: none;
        }

        .nav-tab:first-child {
            border-radius: 10px 0 0 0;
        }

        .nav-tab:last-child {
            border-radius: 0 10px 0 0;
        }

        .nav-tab.active {
            background: #667eea;
            color: white;
        }

        .nav-tab:hover:not(.active) {
            background: #e9ecef;
        }

        .content {
            background: white;
            border-radius: 0 0 10px 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 500px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .btn {
            background: #667eea;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #5a6fd8;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .job-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }

        .job-card h3 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .job-card .job-meta {
            display: flex;
            gap: 1rem;
            margin: 0.5rem 0;
            font-size: 0.9rem;
            color: #666;
        }

        .job-card .job-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .applicant-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 3px solid #28a745;
        }

        .applicant-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }

        .file-links a {
            color: #667eea;
            text-decoration: none;
            margin-right: 1rem;
        }

        .file-links a:hover {
            text-decoration: underline;
        }

        .back-btn {
            background: #6c757d;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs {
                flex-direction: column;
            }
            
            .nav-tab:first-child {
                border-radius: 10px 10px 0 0;
            }
            
            .nav-tab:last-child {
                border-radius: 0 0 0 0;
            }
            
            .container {
                padding: 1rem;
            }
        }    </style>
</head>
<body>

    <header>
        <a href="halamanUtama.php"><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></a>
        <h1>
            <a href="halamanUtama.php">SahabatKarier</a>
        </h1>
        <nav>
            <?php 
                $username = explode('@', $_SESSION['email'])[0];
            ?>
            <span style="color: white; margin-right: 1em;">Selamat datang, <?php echo htmlspecialchars($company['nama_perusahaan']); ?></span>
            <a id="Logout" href="logout.php"><i class='bx bx-log-out'></i>&ThickSpace;Logout</a>
        </nav>
    </header>

    <main>        <div class="dashboard-container">
            <h1 style="text-align: center; margin-bottom: 2rem; color: #667eea;">
                <i class='bx bx-buildings'></i> Dashboard Perusahaan
            </h1>
            
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

        <div class="nav-tabs">
            <a href="?page=dashboard" class="nav-tab <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                <i class='bx bx-tachometer'></i> Dashboard
            </a>
            <a href="?page=jobs" class="nav-tab <?php echo $current_page === 'jobs' ? 'active' : ''; ?>">
                <i class='bx bx-briefcase'></i> Kelola Lowongan
            </a>
            <a href="?page=add_job" class="nav-tab <?php echo $current_page === 'add_job' ? 'active' : ''; ?>">
                <i class='bx bx-plus'></i> Tambah Lowongan
            </a>
            <a href="?page=applicants" class="nav-tab <?php echo $current_page === 'applicants' ? 'active' : ''; ?>">
                <i class='bx bx-group'></i> Semua Pelamar
            </a>
        </div>        <div class="content">
            <?php if (isset($show_profile_form) && $show_profile_form): ?>
                <h2><i class='bx bx-user-plus'></i> Lengkapi Profil Perusahaan</h2>
                <p>Selamat datang! Untuk dapat mengelola lowongan, silakan lengkapi profil perusahaan Anda terlebih dahulu.</p>
                
                <form method="POST" style="max-width: 600px; margin: 2rem 0;">
                    <div class="form-group">
                        <label for="nama_perusahaan">Nama Perusahaan *</label>
                        <input type="text" id="nama_perusahaan" name="nama_perusahaan" placeholder="Masukkan nama perusahaan" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat Perusahaan *</label>
                        <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap perusahaan" required></textarea>
                    </div>
                    
                    <button type="submit" name="create_profile" class="btn">
                        <i class='bx bx-save'></i> Buat Profil Perusahaan
                    </button>
                </form>
                
            <?php elseif ($current_page === 'dashboard'): ?>
                <h2><i class='bx bx-tachometer'></i> Dashboard Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $stats['total_jobs']; ?></h3>
                        <p>Total Lowongan</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $stats['total_applications']; ?></h3>
                        <p>Total Pelamar</p>
                    </div>
                </div>

                <h3>Lowongan Terbaru</h3>
                <?php 
                $recent_jobs = $conn->prepare("SELECT p.*, COUNT(l.id_lamaran) as applicant_count FROM pekerjaan p LEFT JOIN lamaran l ON p.id_pekerjaan = l.id_pekerjaan WHERE p.id_perusahaan = ? GROUP BY p.id_pekerjaan ORDER BY p.tanggal_uploud DESC LIMIT 3");
                $recent_jobs->bind_param("i", $company_id);
                $recent_jobs->execute();
                $recent_result = $recent_jobs->get_result();
                
                if ($recent_result->num_rows > 0):
                    while ($job = $recent_result->fetch_assoc()):
                ?>
                    <div class="job-card">
                        <h3><?php echo htmlspecialchars($job['judul_pekerjaan']); ?></h3>
                        <div class="job-meta">
                            <span><i class='bx bx-category'></i> <?php echo htmlspecialchars($job['kategori']); ?></span>
                            <span><i class='bx bx-time'></i> <?php echo htmlspecialchars($job['jenis_pekerjaan']); ?></span>
                            <span><i class='bx bx-group'></i> <?php echo $job['applicant_count']; ?> Pelamar</span>
                        </div>
                        <div class="job-actions">
                            <a href="?job_detail=<?php echo $job['id_pekerjaan']; ?>" class="btn btn-small">Lihat Detail</a>
                        </div>                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p>Belum ada lowongan yang dibuat.</p>
                <?php endif; ?>
                
            <?php elseif ($current_page === 'jobs'): ?>
                <h2><i class='bx bx-briefcase'></i> Kelola Lowongan</h2>
                
                <?php if ($company_id == 0): ?>
                    <div class="message error">
                        Silakan lengkapi profil perusahaan terlebih dahulu untuk dapat mengelola lowongan.
                        <a href="?page=dashboard" class="btn" style="margin-left: 1rem;">Lengkapi Profil</a>
                    </div>
                <?php elseif ($jobs_result && $jobs_result->num_rows > 0): ?>
                    <?php while ($job = $jobs_result->fetch_assoc()): ?>
                        <div class="job-card">
                            <h3><?php echo htmlspecialchars($job['judul_pekerjaan']); ?></h3>
                            <div class="job-meta">
                                <span><i class='bx bx-category'></i> <?php echo htmlspecialchars($job['kategori']); ?></span>
                                <span><i class='bx bx-time'></i> <?php echo htmlspecialchars($job['jenis_pekerjaan']); ?></span>
                                <span><i class='bx bx-dollar'></i> Rp <?php echo number_format($job['gaji_minimum'], 0, ',', '.'); ?> - <?php echo number_format($job['gaji_maksimum'], 0, ',', '.'); ?></span>
                                <span><i class='bx bx-group'></i> <?php echo $job['applicant_count']; ?> Pelamar</span>
                                <span><i class='bx bx-calendar'></i> Deadline: <?php echo date('d/m/Y', strtotime($job['tanggal_deadline'])); ?></span>
                            </div>
                            <div class="job-actions">
                                <a href="?job_detail=<?php echo $job['id_pekerjaan']; ?>" class="btn btn-small">Lihat Detail</a>
                                <a href="?edit_job=<?php echo $job['id_pekerjaan']; ?>" class="btn btn-small">Edit</a>
                                <a href="?delete_job=<?php echo $job['id_pekerjaan']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus lowongan ini?')">Hapus</a>
                            </div>                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Belum ada lowongan yang dibuat. <a href="?page=add_job" class="btn">Tambah Lowongan Pertama</a></p>
                <?php endif; ?>
                
            <?php elseif ($current_page === 'add_job'): ?>
                <h2><i class='bx bx-plus'></i> Tambah Lowongan Baru</h2>
                
                <?php if ($company_id == 0): ?>
                    <div class="message error">
                        Silakan lengkapi profil perusahaan terlebih dahulu untuk dapat menambah lowongan.
                        <a href="?page=dashboard" class="btn" style="margin-left: 1rem;">Lengkapi Profil</a>
                    </div>
                <?php else: ?>
                
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="judul_pekerjaan">Judul Pekerjaan *</label>
                            <input type="text" id="judul_pekerjaan" name="judul_pekerjaan" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori *</label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Teknologi Informasi">Teknologi Informasi</option>
                                <option value="Keuangan">Keuangan</option>
                                <option value="Pemasaran">Pemasaran</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Teknik">Teknik</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="gaji_minimum">Gaji Minimum (Rp) *</label>
                            <input type="number" id="gaji_minimum" name="gaji_minimum" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="gaji_maksimum">Gaji Maksimum (Rp) *</label>
                            <input type="number" id="gaji_maksimum" name="gaji_maksimum" min="0" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jenis_pekerjaan">Jenis Pekerjaan *</label>
                            <select id="jenis_pekerjaan" name="jenis_pekerjaan" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Penuh Waktu">Penuh Waktu</option>
                                <option value="Paruh Waktu">Paruh Waktu</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Remote">Remote</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_deadline">Tanggal Deadline *</label>
                            <input type="date" id="tanggal_deadline" name="tanggal_deadline" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Pekerjaan *</label>
                        <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan tugas dan tanggung jawab pekerjaan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="syarat_kualifikasi">Syarat dan Kualifikasi *</label>
                        <textarea id="syarat_kualifikasi" name="syarat_kualifikasi" placeholder="Jelaskan persyaratan dan kualifikasi yang dibutuhkan..." required></textarea>
                    </div>                    <button type="submit" name="add_job" class="btn">
                        <i class='bx bx-plus'></i> Tambah Lowongan
                    </button>
                </form>
                
                <?php endif; ?>

            <?php elseif ($current_page === 'edit_job' && $edit_job): ?>
                <a href="?page=jobs" class="btn back-btn">
                    <i class='bx bx-arrow-back'></i> Kembali
                </a>
                
                <h2><i class='bx bx-edit'></i> Edit Lowongan</h2>
                
                <form method="POST">
                    <input type="hidden" name="job_id" value="<?php echo $edit_job['id_pekerjaan']; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="judul_pekerjaan">Judul Pekerjaan *</label>
                            <input type="text" id="judul_pekerjaan" name="judul_pekerjaan" value="<?php echo htmlspecialchars($edit_job['judul_pekerjaan']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori *</label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Teknologi Informasi" <?php echo $edit_job['kategori'] === 'Teknologi Informasi' ? 'selected' : ''; ?>>Teknologi Informasi</option>
                                <option value="Keuangan" <?php echo $edit_job['kategori'] === 'Keuangan' ? 'selected' : ''; ?>>Keuangan</option>
                                <option value="Pemasaran" <?php echo $edit_job['kategori'] === 'Pemasaran' ? 'selected' : ''; ?>>Pemasaran</option>
                                <option value="Pendidikan" <?php echo $edit_job['kategori'] === 'Pendidikan' ? 'selected' : ''; ?>>Pendidikan</option>
                                <option value="Kesehatan" <?php echo $edit_job['kategori'] === 'Kesehatan' ? 'selected' : ''; ?>>Kesehatan</option>
                                <option value="Teknik" <?php echo $edit_job['kategori'] === 'Teknik' ? 'selected' : ''; ?>>Teknik</option>
                                <option value="Lainnya" <?php echo $edit_job['kategori'] === 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="gaji_minimum">Gaji Minimum (Rp) *</label>
                            <input type="number" id="gaji_minimum" name="gaji_minimum" value="<?php echo $edit_job['gaji_minimum']; ?>" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="gaji_maksimum">Gaji Maksimum (Rp) *</label>
                            <input type="number" id="gaji_maksimum" name="gaji_maksimum" value="<?php echo $edit_job['gaji_maksimum']; ?>" min="0" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jenis_pekerjaan">Jenis Pekerjaan *</label>
                            <select id="jenis_pekerjaan" name="jenis_pekerjaan" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Penuh Waktu" <?php echo $edit_job['jenis_pekerjaan'] === 'Penuh Waktu' ? 'selected' : ''; ?>>Penuh Waktu</option>
                                <option value="Paruh Waktu" <?php echo $edit_job['jenis_pekerjaan'] === 'Paruh Waktu' ? 'selected' : ''; ?>>Paruh Waktu</option>
                                <option value="Freelance" <?php echo $edit_job['jenis_pekerjaan'] === 'Freelance' ? 'selected' : ''; ?>>Freelance</option>
                                <option value="Remote" <?php echo $edit_job['jenis_pekerjaan'] === 'Remote' ? 'selected' : ''; ?>>Remote</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_deadline">Tanggal Deadline *</label>
                            <input type="date" id="tanggal_deadline" name="tanggal_deadline" value="<?php echo $edit_job['tanggal_deadline']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Pekerjaan *</label>
                        <textarea id="deskripsi" name="deskripsi" required><?php echo htmlspecialchars($edit_job['deskripsi']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="syarat_kualifikasi">Syarat dan Kualifikasi *</label>
                        <textarea id="syarat_kualifikasi" name="syarat_kualifikasi" required><?php echo htmlspecialchars($edit_job['syarat_kualifikasi']); ?></textarea>
                    </div>

                    <button type="submit" name="edit_job" class="btn">
                        <i class='bx bx-save'></i> Simpan Perubahan
                    </button>
                </form>

            <?php elseif ($current_page === 'job_detail' && $job_detail): ?>
                <a href="?page=jobs" class="btn back-btn">
                    <i class='bx bx-arrow-back'></i> Kembali
                </a>
                
                <h2><i class='bx bx-detail'></i> Detail Lowongan: <?php echo htmlspecialchars($job_detail['judul_pekerjaan']); ?></h2>
                
                <div class="job-card">
                    <h3><?php echo htmlspecialchars($job_detail['judul_pekerjaan']); ?></h3>
                    <div class="job-meta">
                        <span><i class='bx bx-category'></i> <?php echo htmlspecialchars($job_detail['kategori']); ?></span>
                        <span><i class='bx bx-time'></i> <?php echo htmlspecialchars($job_detail['jenis_pekerjaan']); ?></span>
                        <span><i class='bx bx-dollar'></i> Rp <?php echo number_format($job_detail['gaji_minimum'], 0, ',', '.'); ?> - <?php echo number_format($job_detail['gaji_maksimum'], 0, ',', '.'); ?></span>
                        <span><i class='bx bx-group'></i> <?php echo $job_detail['applicant_count']; ?> Pelamar</span>
                    </div>
                    
                    <h4>Deskripsi:</h4>
                    <p><?php echo nl2br(htmlspecialchars($job_detail['deskripsi'])); ?></p>
                    
                    <h4>Syarat dan Kualifikasi:</h4>
                    <p><?php echo nl2br(htmlspecialchars($job_detail['syarat_kualifikasi'])); ?></p>
                </div>

                <h3><i class='bx bx-group'></i> Daftar Pelamar (<?php echo count($applicants); ?>)</h3>
                
                <?php if (count($applicants) > 0): ?>
                    <?php foreach ($applicants as $applicant): ?>
                        <div class="applicant-card">
                            <h4><?php echo htmlspecialchars($applicant['nama_lengkap']); ?></h4>
                            <p><i class='bx bx-envelope'></i> <?php echo htmlspecialchars($applicant['email']); ?></p>
                            <p><i class='bx bx-phone'></i> <?php echo htmlspecialchars($applicant['no_telepon']); ?></p>
                            <div class="applicant-meta">
                                <span><i class='bx bx-calendar'></i> Melamar: <?php echo date('d/m/Y H:i', strtotime($applicant['tanggal_lamaran'])); ?></span>
                                <a href="?applicant_detail=<?php echo $applicant['id_lamaran']; ?>" class="btn btn-small">Lihat Detail</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Belum ada pelamar untuk lowongan ini.</p>
                <?php endif; ?>

            <?php elseif ($current_page === 'applicant_detail' && $applicant_detail): ?>
                <a href="javascript:history.back()" class="btn back-btn">
                    <i class='bx bx-arrow-back'></i> Kembali
                </a>
                
                <h2><i class='bx bx-user'></i> Detail Pelamar</h2>
                
                <div class="applicant-card">
                    <h3><?php echo htmlspecialchars($applicant_detail['nama_lengkap']); ?></h3>
                    <p><strong>Melamar untuk:</strong> <?php echo htmlspecialchars($applicant_detail['judul_pekerjaan']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($applicant_detail['email']); ?></p>
                    <p><strong>No. Telepon:</strong> <?php echo htmlspecialchars($applicant_detail['no_telepon']); ?></p>
                    <p><strong>Tanggal Lahir:</strong> <?php echo date('d/m/Y', strtotime($applicant_detail['tanggal_lahir'])); ?></p>
                    <p><strong>Tanggal Melamar:</strong> <?php echo date('d/m/Y H:i', strtotime($applicant_detail['tanggal_lamaran'])); ?></p>
                    
                    <h4>File Dokumen:</h4>
                    <div class="file-links">
                        <?php if (!empty($applicant_detail['cv_path'])): ?>
                            <a href="<?php echo htmlspecialchars($applicant_detail['cv_path']); ?>" target="_blank">
                                <i class='bx bx-file-blank'></i> Lihat CV
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($applicant_detail['portofolio_path'])): ?>
                            <a href="<?php echo htmlspecialchars($applicant_detail['portofolio_path']); ?>" target="_blank">
                                <i class='bx bx-file-blank'></i> Lihat Portofolio
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($applicant_detail['lamaran_path'])): ?>
                            <a href="<?php echo htmlspecialchars($applicant_detail['lamaran_path']); ?>" target="_blank">
                                <i class='bx bx-file-blank'></i> Lihat Surat Lamaran
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($current_page === 'applicants'): ?>
                <h2><i class='bx bx-group'></i> Semua Pelamar</h2>
                
                <?php
                $all_applicants_sql = "SELECT l.*, p.judul_pekerjaan 
                FROM lamaran l 
                JOIN pekerjaan p ON l.id_pekerjaan = p.id_pekerjaan 
                WHERE p.id_perusahaan = ? 
                ORDER BY l.tanggal_lamaran DESC";
                $all_applicants_stmt = $conn->prepare($all_applicants_sql);
                $all_applicants_stmt->bind_param("i", $company_id);
                $all_applicants_stmt->execute();
                $all_applicants_result = $all_applicants_stmt->get_result();
                
                if ($all_applicants_result->num_rows > 0):
                    while ($applicant = $all_applicants_result->fetch_assoc()):
                ?>
                    <div class="applicant-card">
                        <h4><?php echo htmlspecialchars($applicant['nama_lengkap']); ?></h4>
                        <p><strong>Posisi:</strong> <?php echo htmlspecialchars($applicant['judul_pekerjaan']); ?></p>
                        <p><i class='bx bx-envelope'></i> <?php echo htmlspecialchars($applicant['email']); ?></p>
                        <p><i class='bx bx-phone'></i> <?php echo htmlspecialchars($applicant['no_telepon']); ?></p>
                        <div class="applicant-meta">
                            <span><i class='bx bx-calendar'></i> Melamar: <?php echo date('d/m/Y H:i', strtotime($applicant['tanggal_lamaran'])); ?></span>
                            <a href="?applicant_detail=<?php echo $applicant['id_lamaran']; ?>" class="btn btn-small">Lihat Detail</a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p>Belum ada pelamar untuk semua lowongan Anda.</p>
                <?php endif; ?>            <?php endif; ?>
        </div>
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
        // Auto-hide messages after 5 seconds
        const messages = document.querySelectorAll('.message');
        messages.forEach(message => {
            setTimeout(() => {
                message.style.display = 'none';
            }, 5000);
        });

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.style.borderColor = '#dc3545';
                            isValid = false;
                        } else {
                            field.style.borderColor = '#ddd';
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi.');
                    }
                });
            });
        });
    </script>
</body>
</html>
