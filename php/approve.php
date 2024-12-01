<?php

// memanggil connection.php untuk memastikan backend sudah terhubung ke database
require'connection.php';

// jika ada request POST, maka melakukan proses submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize and cast ID to an integer

    // memanggil fungsi untuk mengambil status registrant berdasarkan id user
    $sql = "SELECT status FROM registrant WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // jika status registrant sudah diubah menjadi disapproved maka mengubah status registrant menjadi approved
        $newStatus = ($row['status'] === 'approved') ? 'disapproved' : 'approved';

        // memanggil fungsi untuk memperbarui status registrant
        $sql = "UPDATE registrant SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $newStatus, $id);

        if ($stmt->execute()) {
            // jika eksekusi mysqli berhasil maka menampilkan sukses
            echo json_encode(['status' => 'success', 'pesan' => 'berhasil mengubah status pendaftar', 'newStatus' => $newStatus]);
        } else {
            // jika eksekusi mysqli menghasilkan error maka menampilkan error
            echo json_encode(['status' => 'error', 'pesan' => 'gagal mengubah status pendaftar']);
        }
    } else {
        // jika tidak ada pendaftar dengan id tersebut maka menampilkan error
        echo json_encode(['status' => 'error', 'pesan' => 'tidak ada registrant dengan id tersebut']);
    }

    // tutup statement dan koneksi database
    $stmt->close();
    $conn->close();
} else {
    // jika tidak ada id atau metode request bukan POST maka menampilkan error
    echo json_encode(['status' => 'error', 'pesan' => 'metode request tidak diizinkan']);
}
