<?php
session_start();
include 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: halamanLogin.php');
    exit;
}

// Get user's email if not already in session
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

$id_pekerjaan = isset($_GET['id']) ? (int)$_GET['id'] : 1;

$sql = "SELECT p.*, pr.nama_perusahaan, pr.logo_path
        FROM pekerjaan p 
        JOIN perusahaan pr ON p.id_perusahaan = pr.id_perusahaan 
        WHERE p.id_pekerjaan = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pekerjaan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $job = $result->fetch_assoc();
} else {
    echo "Pekerjaan tidak ditemukan!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="stylePengajuan.css">
    <title>Halaman Pengajuan - <?php echo htmlspecialchars($job['judul_pekerjaan']); ?></title>
</head>

<!--------------------------------------------------------------- HEADER --------------------------------------------------------------->



<body>
    <header>
        <a href="halamanUtama.php"><img id="logoWebsite" src="website_asset/logo_SK.png" alt="Logo SahabatKarier"></a>
        <h1>
            <a href="halamanUtama.php">SahabatKarier</a>
        </h1>
        <nav>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <span style="color: white; margin-right: 1em;">Selamat datang, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                <a id="Logout" href="logout.php"><i class='bx bx-log-out'></i>&ThickSpace;Logout</a>
            <?php else: ?>
                <a id="Register" href="halamanRegister.php"><i class='bx bxs-pencil'></i>&ThickSpace;</i>Register</a>
                <a id="Login" href="halamanLogin.php"><i class='bx bxs-user-circle'>&ThickSpace;</i>Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <div class="shadow">
            <section class="ProfilPerusahaan">
                <h5>Anda sedang melamar untuk:</h5>
                <img src="<?php echo htmlspecialchars($job['logo_path']); ?>" alt="<?php echo htmlspecialchars($job['nama_perusahaan']); ?>" width="100px">
                <h2><?php echo htmlspecialchars($job['judul_pekerjaan']); ?></h2>
                <p><i class='bx bx-check-shield' style="color: green">&ThickSpace; &ThickSpace;</i><?php echo htmlspecialchars($job['nama_perusahaan']); ?></p>
            </section>
            <div class="OpsiDaftar">
                <div class="Lamaran">
                    <a href="halamanDetail.php?id=<?php echo $job['id_pekerjaan']; ?>">Kembali ke Detail</a>
                </div>
            </div>
        </div>


        <!--------------------------------------------------------------- FORMULIR --------------------------------------------------------------->
        <section class="container">
            <h2 class="form-lamaran">Formulir Lamaran</h2>
            <form action="submit.php" method="post" class="form" enctype="multipart/form-data">
                <input type="hidden" name="id_pekerjaan" value="<?php echo $job['id_pekerjaan']; ?>">
                <div class="input-box">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama_lengkap" placeholder="Masukkan nama lengkap anda" />
                    <p id="nama-error" class="field-error"></p>
                </div>
                <div class="input-box">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" min="1900-01-01" max="2025-06-10" />
                    <p id="tanggal_lahir-error" class="field-error"></p>
                </div>
                <div class="input-box">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" placeholder="Masukkan e-mail anda" <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?> />
                    <p id="email-error" class="field-error"></p>
                </div>
                <div class="input-box">
                    <label for="telepon">Nomor HP</label>
                    <input type="tel" id="telepon" name="no_telepon" placeholder="Masukkan nomor HP anda" pattern="[0-9]+" />
                    <p id="telepon-error" class="field-error"></p>
                </div>
                <div class="input-box">
                    <label for="cv">CV</label>
                    <input id="cv" name="cv" type="file" accept=".pdf" />
                    <p id="format_file">Format file yang diterima: pdf (maksimal 5MB)</p>
                    <p id="cv-error" class="file-error"></p>
                </div>
                <div class="input-box">
                    <label for="portofolio">Portofolio <span id="Opsional"> (Opsional) </span></label>
                    <input id="portofolio" name="portofolio" type="file" accept=".pdf" />
                    <p id="format_file">Format file yang diterima: pdf (maksimal 5MB)</p>
                    <p id="portofolio-error" class="file-error"></p>
                </div>
                <div class="input-box">
                    <label for="surat_lamaran">Surat Lamaran<span id="Opsional"> (Opsional) </span></label>
                    <input id="surat_lamaran" name="surat_lamaran" type="file" accept=".pdf" />
                    <p id="format_file">Format file yang diterima: pdf (maksimal 5MB)</p>
                    <p id="surat_lamaran-error" class="file-error"></p>
                </div>

                <button type="submit">Kirim Lamaran</button>
            </form>
        </section>
    </main>
    <script>
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`${fieldId}-error`);

            field.classList.add('input-error');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function clearFieldValidation(fieldId) {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`${fieldId}-error`);

            field.classList.remove('input-error');
            errorElement.style.display = 'none';
        }
        function scrollToFirstInvalidField() {
            const invalidFields = document.querySelectorAll('.input-error, .file-input-error');

            if (invalidFields.length > 0) {
                const targetField = invalidFields[0];
                const fieldRect = targetField.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                const targetPosition = window.pageYOffset + fieldRect.top - (viewportHeight / 2) + (fieldRect.height / 2);

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });

                setTimeout(() => {
                    try {
                        if (!targetField.readOnly && !targetField.disabled) {
                            targetField.focus();
                        }
                    } catch (e) {
                    }

                    targetField.style.animation = 'shake 0.5s ease-in-out';

                    setTimeout(() => {
                        targetField.style.animation = '';
                    }, 500);
                }, 600);
            }
        }

        function validateField(fieldId) {
            const field = document.getElementById(fieldId);
            const value = field.value.trim();

            switch (fieldId) {
                case 'nama':
                    if (!value) {
                        showFieldError(fieldId, 'Nama lengkap wajib diisi');
                        return false;
                    } else if (value.length < 2) {
                        showFieldError(fieldId, 'Nama lengkap minimal 2 karakter');
                        return false;
                    } else {
                        clearFieldValidation(fieldId);
                        return true;
                    }

                case 'tanggal_lahir':
                    if (!value) {
                        showFieldError(fieldId, 'Tanggal lahir wajib diisi');
                        return false;
                    } else {
                        const birthDate = new Date(value);
                        const today = new Date();
                        const age = today.getFullYear() - birthDate.getFullYear();

                        if (age < 17) {
                            showFieldError(fieldId, 'Usia minimal 17 tahun');
                            return false;
                        } else if (age > 65) {
                            showFieldError(fieldId, 'Usia maksimal 65 tahun');
                            return false;
                        } else {
                            clearFieldValidation(fieldId);
                            return true;
                        }
                    }
                case 'email':
                    if (field.readOnly && value) {
                        clearFieldValidation(fieldId);
                        return true;
                    }

                    if (!value) {
                        showFieldError(fieldId, 'Email wajib diisi');
                        return false;
                    } else if (!field.checkValidity()) {
                        showFieldError(fieldId, 'Format email tidak valid');
                        return false;
                    } else {
                        clearFieldValidation(fieldId);
                        return true;
                    }

                case 'telepon':
                    if (!value) {
                        showFieldError(fieldId, 'Nomor HP wajib diisi');
                        return false;
                    } else if (!/^[0-9]{10,15}$/.test(value)) {
                        showFieldError(fieldId, 'Nomor HP harus berisi 10-15 digit angka');
                        return false;
                    } else {
                        clearFieldValidation(fieldId);
                        return true;
                    }

                default:
                    return true;
            }
        }

        function validatePDFFile(fileInput, fieldName) {
            const file = fileInput.files[0];
            const inputId = fileInput.id;
            const errorElement = document.getElementById(`${inputId}-error`);

            errorElement.style.display = 'none';
            fileInput.classList.remove('file-input-error');

            if (!file) return true;

            const maxSize = 5 * 1024 * 1024;
            const allowedTypes = ['application/pdf'];

            if (file.size > maxSize) {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                errorElement.textContent = `File terlalu besar (${fileSizeMB}MB). Maksimal 5MB.`;
                errorElement.style.display = 'block';
                fileInput.classList.add('file-input-error');
                fileInput.value = '';
                return false;
            }

            if (!allowedTypes.includes(file.type)) {
                errorElement.textContent = `File harus PDF.`;
                errorElement.style.display = 'block';
                fileInput.classList.add('file-input-error');
                fileInput.value = '';
                return false;
            }

            const fileName = file.name.toLowerCase();
            if (!fileName.endsWith('.pdf')) {
                errorElement.textContent = `File harus memiliki ekstensi .pdf`;
                errorElement.style.display = 'block';
                fileInput.classList.add('file-input-error');
                fileInput.value = '';
                return false;
            }

            return true;
        }
        ['nama', 'tanggal_lahir', 'email', 'telepon'].forEach(fieldId => {
            const field = document.getElementById(fieldId);

            field.addEventListener('blur', function() {
                validateField(fieldId);
            });

            field.addEventListener('input', function() {
                clearFieldValidation(fieldId);
            });
        });
        document.getElementById('cv').addEventListener('change', function() {
            validatePDFFile(this, 'CV');
        });

        document.getElementById('cv').addEventListener('blur', function() {
            if (this.files.length === 0) {
                const errorElement = document.getElementById('cv-error');
                errorElement.textContent = 'CV wajib diupload';
                errorElement.style.display = 'block';
                this.classList.add('file-input-error');
            }
        });

        document.getElementById('cv').addEventListener('click', function() {
            const errorElement = document.getElementById('cv-error');
            errorElement.style.display = 'none';
            this.classList.remove('file-input-error');
        });

        document.getElementById('portofolio').addEventListener('change', function() {
            validatePDFFile(this, 'Portofolio');
        });

        document.getElementById('surat_lamaran').addEventListener('change', function() {
            validatePDFFile(this, 'Surat Lamaran');
        });

        document.querySelector('.form').addEventListener('submit', function(e) {
            let isValid = true;

            ['nama', 'tanggal_lahir', 'email', 'telepon'].forEach(fieldId => {
                if (!validateField(fieldId)) {
                    isValid = false;
                }
            });

            const cvInput = document.getElementById('cv');
            const portofolioInput = document.getElementById('portofolio');
            const suratLamaranInput = document.getElementById('surat_lamaran');
            if (cvInput.files.length === 0) {
                const errorElement = document.getElementById('cv-error');
                errorElement.textContent = 'CV wajib diupload';
                errorElement.style.display = 'block';
                cvInput.classList.add('file-input-error');
                isValid = false;
            } else if (!validatePDFFile(cvInput, 'CV')) {
                isValid = false;
            }

            if (portofolioInput.files.length > 0 && !validatePDFFile(portofolioInput, 'Portofolio')) {
                isValid = false;
            }

            if (suratLamaranInput.files.length > 0 && !validatePDFFile(suratLamaranInput, 'Surat Lamaran')) {
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                scrollToFirstInvalidField();
                return false;
            }

            return true;
        });

        document.querySelectorAll('input[type="file"]').forEach(function(input) {
            input.addEventListener('click', function() {
                this.classList.remove('file-input-error');
                const errorElement = document.getElementById(`${this.id}-error`);
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            });
        });
    </script>
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