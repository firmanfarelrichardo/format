<?php
function connect_to_database() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "oai";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        // Hentikan eksekusi dan log error tanpa menampilkan detail sensitif ke user
        error_log("Koneksi database gagal: " . $conn->connect_error);
        die("Koneksi ke database gagal. Silakan coba lagi nanti.");
    }
    
    return $conn;
}
?>