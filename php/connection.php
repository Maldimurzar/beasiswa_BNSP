<?php

// membuat konfigurasi untuk melakukan koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$database = "bnsp";

// melakukan koneksi ke database
$conn = new mysqli($servername, $username, $password, $database);

// jika ada error koneksi, kirim hasil error ke user
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
