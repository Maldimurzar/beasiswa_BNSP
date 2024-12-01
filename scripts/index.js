// Memanggil form menggunakan id dari form
const form = document.getElementById('myForm');

// fungsi untuk melakukan proses submit form
form.addEventListener('submit', function (event) {
  // menghindari kebiasaan default/awal dari submit form
  event.preventDefault();

  // mengambil data yang didapatkan dari form
  const formData = new FormData(form);

  /*
    melakukan submit form ke backend dengan
    metode POST yang berarti 
    fungsi ini akan menambahkan data baru ke database
  */
  fetch('php/create.php', {
    method: 'POST',
    body: formData,
  })
    // menunggu jawaban hasil dari backend
    .then(async response => {
      // jika terdapat error dari hasil backend, maka tampilkan error
      if (!response.ok) {
        return response.json().then(errorData => {
          throw new Error(errorData.pesan);
        });
      }
      // membuat hasil dari backend sebagai objek JSON (javascript object notation)
      return response.json();
    })
    // menunggu hasil yang sudah diubah menjadi objek JSON
    .then(data => {
      console.log(data);
      // menampilkan notifikasi sukses ke user
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: data.pesan
      });
      // reset form setelah sukses menambahkan data
      document.getElementById("myForm").reset();
    })
    // menangkap unexpected error yang terjadi saat melakukan submit form
    .catch(error => {
      console.error('Error:', error);
      // menampikan notifkasi error ke user
      Swal.fire({
        icon: 'error',
        title: 'Submission Failed',
        text: error.pesan
      });
    });
});

// fungsi untuk membuat random ipk
function generateRandomIPK() {
  // melakukan randomisasi antara 2 dan 4
  const randomIPK = (Math.random() * 2 + 2).toFixed(2);
  // memanggil element input dengan id 'ipk' dan mengubah nilainya berdasarkan hasil dari randomisasi ipk
  document.getElementById('ipk').value = randomIPK;

  // melakukan pengecekan apakah ipk yang dihasilkan adalah lebih dari 3
  if (parseFloat(randomIPK) < 3) {
    // jika ipk kurang dari 3, maka menonaktifkan 'status beasiswa' dan menghapus nilai yang ada
    document.getElementById('beasiswa').disabled = true;
    document.getElementById('beasiswa').value = null;
  } else {
    // jika ipk lebih dari 3 maka menonaktifkan 'status beasiswa' dan menghapus nilai yang ada
    document.getElementById('beasiswa').disabled = false;
  }
}

// fungsi untuk melakukan reset form
function resetForm() {
  document.getElementById('myForm').reset();
}
