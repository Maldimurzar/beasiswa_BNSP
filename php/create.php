<?php

// memanggil connection.php untuk memastikan backend sudah terhubung ke database
require'connection.php';

// menambahkan header untuk menampilkan data dalam format JSON
header('Content-Type: application/json');

// membuat array untuk menampung hasil dari proses submit form
$response = [];

// jika ada request POST, maka melakukan proses submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // mengumpulkan data yang dikirimkan dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $telp = $_POST['telp'];
    $semester = (int)$_POST['semester'];
    $ipk = (float)$_POST['ipk'];
    $beasiswa = $_POST['beasiswa'];

    // fungsi untuk melakukan proses upload berkas
    if (isset($_FILES['berkas'])) {
        // upload berkas ke folder 'uploads' di dalam direktori publik
        $targetDir = "../public/uploads/";
        $fileName = basename($_FILES["berkas"]["name"]);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // jika folder 'uploads' tidak ada, maka buat folder tersebut
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // tipe berkas yang diizinkan
        $allowedTypes = array('jpg', 'jpeg', 'png', 'pdf');
        // jika berkas yang dikirimkan adalah bertipe yang valid, maka upload berkas tersebut ke folder 'uploads'
        if (in_array($fileType, $allowedTypes)) {
            // pindahkan file yang sudah dikirim ke folder 'uploads'
            if (move_uploaded_file($_FILES["berkas"]["tmp_name"], $targetFile)) {
                $response['file_upload'] = "The file $fileName has been uploaded.";
            } else {
                // jika gagal upload berkas, maka kirim error ke user
                http_response_code(400);
                echo json_encode(
                    ['status' => 'error', 'pesan' => 'Terjadi kesalahan saat upload berkas.']
                );
                exit;
            }
        } else {
            // jika berkas yang dikirimkan tidak sesuai dengan tipe yang diizinkan, maka kirim error ke user
            http_response_code(400);
            echo json_encode(
                ['status' => 'error', 'pesan' => 'Jenis berkas yang dikirimkan tidak sesuai dengan tipe yang diizinkan.']
            );
            exit;
        }
    }

    // memasukkan data ke dalam variabel sql
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $telp = $conn->real_escape_string($telp);
    $beasiswa = $conn->real_escape_string($beasiswa);

    // fungsi kode sql untuk membuat record baru sesuai dengan form yang telah dikirimkan
    $sql = "INSERT INTO registrant (name, email, telp, semester, ipk, berkas, beasiswa) 
            VALUES ('$name', '$email', '$telp', $semester, $ipk, 'uploads/$fileName', '$beasiswa')";

    // jika proses mysql query berhasil, maka kirimkan response sukses ke user
    if ($conn->query($sql) === true) {
        $response['status'] = 'success';
        $response['pesan'] = 'Data pendaftar berhasil dibuat.';
    } else {
        // jika terdapat error, maka kirimkan response error ke user
        http_response_code(500);
        echo json_encode(
            ['status' => 'error', 'pesan' => 'terjadi kesalahan pada database: '. $conn->error]
        );
        // keluar dari program
        exit;
    }

    // tutup koneksi ke database
    $conn->close();
} else {
    // jika metode requestnya bukan POST, maka kirimkan response error ke user
    http_response_code(405);
    echo json_encode(
        ['status' => 'error', 'pesan' => 'metode request tidak diizinkan']
    );
    // keluar dari program
    exit;
}

// kirimkan hasil json ke user
echo json_encode($response);
