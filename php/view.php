<?php

// memanggil connection.php untuk memastikan backend sudah terhubung ke database
require'connection.php';

// membuat query untuk menampilkan data pendaftar dari database
$sql = "SELECT id, name, email, telp, semester, ipk, status, beasiswa, berkas FROM registrant";
$result = $conn->query($sql);
?>

<!-- membuat tampilan untuk user untuk melihat data pendaftar berupa tabel -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Registrant List</title>
</head>

<body class="bg-light">
  <div class="container py-5">
  <?php require '../components/navbar.html'; ?>

  <div class="container my-5">
    <h1 class="mb-4 text-center">List Pendaftar</h1>
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Semester</th>
            <th>IPK</th>
            <th>Status</th>
            <th>Tipe Beasiswa</th>
            <th>Berkas</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // memasukkan data dari query ke variabel
                    $status = htmlspecialchars($row["status"]);
                    // jika nilai dari kolom 'beasiswa' adalah null/kosong, maka menggunakan '-'
                    $beasiswa = $row["beasiswa"] ? htmlspecialchars($row["beasiswa"]) : '-';

                    // jika status registrant adalah 'approved', maka button text adalah 'disapprove' dan button class adalah 'btn-danger'
                    if ($status == 'approved') {
                        $buttonText = 'Disapprove';
                        $buttonClass = 'btn-danger';
                        $action = 'toggleApproval';
                    } else {
                        // jika status registrant adalah 'disapproved', maka button text adalah 'approve' dan button class adalah 'btn-success'
                        $buttonText = 'Approve';
                        $buttonClass = 'btn-success';
                        $action = 'toggleApproval';
                    }

                    // tampilkan data pendaftar berupa tabel menggunakan variabel yang telah dibuat sebelumnya
                    echo '<tr>
                            <td>' . $row["id"] . '</td>
                            <td>' . htmlspecialchars($row["name"]) . '</td>
                            <td>' . htmlspecialchars($row["email"]) . '</td>
                            <td>' . htmlspecialchars($row["telp"]) . '</td>
                            <td>' . htmlspecialchars($row["semester"]) . '</td>
                            <td>' . htmlspecialchars($row["ipk"]) . '</td>
                            <td>' . htmlspecialchars($row["status"]) . '</td>
                            <td>' . $beasiswa . '</td> <!-- Beasiswa Type Column -->
                            <td><a href="/bnsp/public/' . htmlspecialchars($row["berkas"]) . '" target="_blank" class="btn btn-sm btn-info">View File</a></td>
                            <td>
                                <button id="approve-button-' . $row['id'] . '" class="btn ' . $buttonClass . '" onclick="' . $action . '(' . $row['id'] . ')">
                                    ' . $buttonText . '
                                </button>
                            </td>
                        </tr>';
                }
            } else {
                // jika tidak ada data, tampilkan pesan 'No records found'
                echo "<tr><td colspan='10' class='text-center'>Tidak Ada Data Pendaftar</td></tr>";
            }
// menutup koneksi ke database
$conn->close()
?>
        </tbody>
      </table>
    </div>
  </div>
</div>

  <script src="/bnsp/scripts/approve_registrant.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>
