<?php

require_once "koneksi.php";

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

$stmt = $conn->prepare(
    "SELECT * FROM Login WHERE Username = ? AND Password = ?"
);

$stmt->bind_param("ss", $username, $password);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Login berhasil!";
} else {
    echo "Username atau password salah!";
}

$stmt->close();
$conn->close();

?>