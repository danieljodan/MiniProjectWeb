function seePassword() {
    var password = document.getElementById("passwordID");
    var showPassword = document.getElementById("showPasswordID");
    if (showPassword.checked) {
        password.type = "text";
    } else {
        password.type = "password";
    }
}