<?php
session_start();
include 'Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($action === 'login') {
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                echo "success";
            } else {
                echo "Password salah!";
            }
        } else {
            echo "Username tidak ditemukan!";
        }
        exit;
    }

    if ($action === 'register') {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "Username sudah digunakan!";
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);

        if ($stmt->execute()) {
            echo "registered";
        } else {
            echo "Gagal mendaftar.";
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login / Register Buku Tamu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            min-height: 100vh;
        }
        .form-card {
            max-width: 420px;
            margin: auto;
            margin-top: 100px;
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="form-card">
    <h3 class="text-center mb-4" id="formTitle">Login Buku Tamu</h3>
    <div id="message"></div>

    <!-- Login Form -->
    <form id="loginForm">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <input type="hidden" name="action" value="login">
        <div class="d-grid mb-2">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
        <p class="text-center">
            Belum punya akun?
            <a href="#" onclick="toggleForms()">Daftar di sini</a>
        </p>
    </form>

    <!-- Register Form -->
    <form id="registerForm" class="hidden">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <input type="hidden" name="action" value="register">
        <div class="d-grid mb-2">
            <button type="submit" class="btn btn-success">Register</button>
        </div>
        <p class="text-center">
            Sudah punya akun?
            <a href="#" onclick="toggleForms()">Login di sini</a>
        </p>
    </form>
</div>

<script>
function toggleForms() {
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const formTitle = document.getElementById("formTitle");

    loginForm.classList.toggle("hidden");
    registerForm.classList.toggle("hidden");

    formTitle.textContent = loginForm.classList.contains("hidden")
        ? "Daftar Akun Buku Tamu"
        : "Login Buku Tamu";

    document.getElementById("message").innerHTML = "";
}

document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const data = new FormData(this);

    fetch("login.php", {
        method: "POST",
        body: data
    }).then(res => res.text())
      .then(msg => {
        if (msg.trim() === "success") {
            window.location.href = "index.php";
        } else {
            document.getElementById("message").innerHTML =
                `<div class="alert alert-danger">${msg}</div>`;
        }
    });
});

document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const data = new FormData(this);

    fetch("login.php", {
        method: "POST",
        body: data
    }).then(res => res.text())
      .then(msg => {
        if (msg.trim() === "registered") {
            toggleForms();
            document.getElementById("message").innerHTML =
                `<div class="alert alert-success">Pendaftaran berhasil! Silakan login.</div>`;
        } else {
            document.getElementById("message").innerHTML =
                `<div class="alert alert-danger">${msg}</div>`;
        }
    });
});
</script>
</body>
</html>
