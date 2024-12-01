function toggleApproval(id) {
  /*
    melakukan submit form ke backend dengan
    metode POST yang berarti 
    fungsi ini akan menambahkan data baru ke database
  */
  fetch('approve.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'id=' + id
  })
    // menunggu jawaban hasil dari backend dan membuat hasilnya sebagai objek JSON (javascript object notation)
    .then(response => response.json())
    // menunggu hasil yang sudah diubah menjadi objek JSON
    .then(data => {
      // jika berhasil mengubah status registrant, maka tampilkan notifikasi sukses ke user
      if (data.status === 'success') {
        // memanggil element button untuk diubah statusnya berdasarkan kriteria
        const button = document.getElementById('approve-button-' + id);

        // jika status registrant baru adalah 'approved', maka mengubah button menjadi 'disapprove'
        if (data.newStatus === 'approved') {
          button.textContent = 'Disapprove';
          button.classList.remove('btn-success');
          button.classList.add('btn-danger');
          button.setAttribute('onclick', 'toggleApproval(' + id + ')'); // Update action to disapprove

          // tampilkan notifikasi sukses ke user
          Swal.fire({
            icon: 'success',
            title: 'berhasil mengubah status',
            text: `Pendaftar telah di ${data.newStatus}.`,
          });
        } else {
          // jika status registrant baru adalah 'disapprove', maka mengubah button menjadi 'approve'
          button.textContent = 'Approve';
          button.classList.remove('btn-danger');
          button.classList.add('btn-success');
          button.setAttribute('onclick', 'toggleApproval(' + id + ')'); // Update action to approve

          // tampilkan notifikasi sukses ke user
          Swal.fire({
            icon: 'success',
            title: 'berhasil mengubah status',
            text: `Pendaftar telah di ${data.newStatus}.`,
          });
        }

        // menunggu selama 1.5 detik sebelum memuat ulang halaman untuk memungkinkan user untuk melihat notifikasi sukses
        // dan untuk merubah tampilan approved/disapproved button
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      } else {
        // jika ada error, tampilkan notifikasi error ke user
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Terjadi kesalahan saat mengubah status pendaftar.',
        });
      }
    })
    // menangkap unexpected error yang terjadi saat melakukan submit form
    .catch(error => {
      console.error('Error:', error);
      // menampikan notifkasi error ke user
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat mengubah status pendaftar.',
      });
    });
}
