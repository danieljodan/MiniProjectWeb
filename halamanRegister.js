function konfirmasiEmailPassword() {
    var email = document.getElementById("emailID").value;
    var password = document.getElementById("passwordID").value;
    var passwordKonfirm = document.getElementById("passwordKonfirmID").value;
    var role = document.querySelector('input[name="role"]:checked');
    var keterangan = document.getElementById("keteranganID");

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,100}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email || !password || !passwordKonfirm || !role) {
        keterangan.innerHTML = "Semua Field Harus Diisi!";
        return false;
    }
    if (!emailRegex.test(email)) {
        keterangan.innerHTML = "Email Tidak Valid!";
        return false;
    }
    if (!passwordRegex.test(password)) {
        keterangan.innerHTML = "Kata Sandi Minimal 8 Karakter, Harus Terdapat Huruf Besar dan Kecil, Angka, dan Simbol";
        return false;
    }
    if (password != passwordKonfirm) {
        keterangan.innerHTML = "Password dan Konfirmasi Password tidak sama!";
        return false;
    }

    return true;
}

function seePassword() {
    var password = document.getElementById("passwordID");
    var passwordKonfirm = document.getElementById("passwordKonfirmID");
    var showPassword = document.getElementById("showPasswordID");
    if (showPassword.checked) {
        password.type = "text";
        passwordKonfirm.type = "text";
    } else {
        password.type = "password";
        passwordKonfirm.type = "password";
    }
}