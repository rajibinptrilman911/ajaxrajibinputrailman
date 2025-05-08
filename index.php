<?php
session_start();
include 'Connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Handle pengiriman pesan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO entries (name, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $message);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Buku Tamu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            min-height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .messages {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="card">
    <h4 class="mb-3 text-center">Halo, <?= htmlspecialchars($username) ?> 👋</h4>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
        </div>
        <div class="mb-3">
            <textarea name="message" class="form-control" rows="3" placeholder="Tulis pesan..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
    </form>

    <div class="messages">
        <h5 class="mb-3">Daftar Pesan</h5>
        <?php
        $result = $conn->query("SELECT * FROM entries ORDER BY created_at DESC");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='border rounded p-2 mb-2'>";
                echo "<strong>" . htmlspecialchars($row['name']) . "</strong><br>";
                echo "<small class='text-muted'>" . $row['created_at'] . "</small>";
                echo "<p class='mb-0'>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Belum ada pesan.</p>";
        }
        ?>
    </div>

    <a href="logout.php" class="btn btn-danger w-100 mt-4">Logout</a>
</div>

</body>
</html>
