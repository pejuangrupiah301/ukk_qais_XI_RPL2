
<?php
session_start();
include('koneksi.php');

// Cek apakah form telah disubmit dengan tombol "tambah"
if (isset($_POST['tambah'])) {
    // Ambil data dari form
    $kode_buku = $_POST['kode_buku'];
    $no_buku = $_POST['no_buku'];
    $judul_buku = $_POST['judul_buku'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // --- Logika Upload Gambar ---
    $gambar_buku = ''; // Default kosong jika tidak ada file diunggah
    if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] == 0) {
        $target_dir = "uploads/"; // Direktori untuk menyimpan gambar
        if (!is_dir($target_dir)) { // Buat folder jika belum ada
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["gambar_buku"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name; // Tambahkan ID unik agar nama file tidak bentrok
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file benar-benar gambar
        $check = getimagesize($_FILES["gambar_buku"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        //  ukuran file maksimal (5MB)
        if ($_FILES["gambar_buku"]["size"] > 5000000) {
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // format yang di izinkan
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Jika gagal upload
        if ($uploadOk == 0) {
            echo "Maaf, file Anda tidak berhasil diunggah.";
        // Jika semua pengecekan lolos, lakukan upload
        } else {
            if (move_uploaded_file($_FILES["gambar_buku"]["tmp_name"], $target_file)) {
                $gambar_buku = $target_file; // Simpan path gambar untuk dimasukkan ke database
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah file.";
            }
        }
    }
    // --- Akhir Logika Upload Gambar ---

    // Amankan input sebelum dimasukkan ke database
    $kode_buku = mysqli_real_escape_string($koneksi, $kode_buku);
    $judul_buku = mysqli_real_escape_string($koneksi, $judul_buku);
    $penulis = mysqli_real_escape_string($koneksi, $penulis);
    $penerbit = mysqli_real_escape_string($koneksi, $penerbit);
    
    // Catatan: angka tidak wajib di-escape jika dikonversi ke integer
    // $no_buku = (int)$no_buku; // Contoh casting ke int
    // $tahun_terbit = (int)$tahun_terbit;
    // $jumlah_halaman = (int)$jumlah_halaman;
    // $harga = (int)$harga;

    // Query untuk memasukkan data buku ke database
    $sql = "INSERT INTO buku(kode_buku, no_buku, judul_buku, tahun_terbit, penulis, penerbit, jumlah_halaman, stok, harga, gambar_buku)
            VALUES ('$kode_buku','$no_buku','$judul_buku','$tahun_terbit','$penulis','$penerbit', '$jumlah_halaman','$stok','$harga','$gambar_buku')";

    // Jalankan query dan cek apakah berhasil
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Buku berhasil ditambahkan!');window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal Ditambahkan!'); </script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        height: 100vh;
        background-color: #000;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ffffff;
        overflow: auto;
        padding: 20px;
    }

    form {
        background-color: #0a0a0a;
        padding: 40px;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 0 20px #00bfff50, 0 0 40px #00bfff20, inset 0 0 10px #00bfff20;
        position: relative;
    }

    form::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, #00bfff, #1e90ff, #00bfff, #1e90ff);
        background-size: 400%;
        filter: blur(10px);
        animation: glowing 20s linear infinite;
        z-index: -1;
        border-radius: 14px;
    }

    form h2 {
        text-align: center;
        color: #00bfff;
        margin-bottom: 20px;
        text-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0 20px;
        background-color: #111;
        border: none;
        border-bottom: 2px solid #00bfff;
        color: #00bfff;
        font-size: 15px;
        outline: none;
        transition: 0.3s;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="file"]:focus {
        background-color: #151515;
        border-bottom: 2px solid #1e90ff;
        color: #00bfff;
        box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
    }

    label {
        display: block;
        text-align: left;
        color: #00bfff;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #00bfff;
        color: white;
        font-weight: bold;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
        transition: background 0.3s, box-shadow 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #1e90ff;
        box-shadow: 0 0 15px #1e90ff, 0 0 30px #00bfff;
    }

    @keyframes glowing {
        0% { background-position: 0 0; }
        50% { background-position: 400% 0; }
        100% { background-position: 0 0; }
    }
</style>


</head>
<body>
     <form action="tambah_buku.php" method="post" enctype="multipart/form-data"> <h2>Tambah Buku Baru</h2> <input type="text" name="kode_buku" placeholder="Kode Buku" required>
    <input type="number" name="no_buku" placeholder="Nomor Buku" required>
    <input type="text" name="judul_buku" placeholder="Judul Buku" required>
    <input type="number" name="tahun_terbit" placeholder="Tahun Terbit" required>
    <input type="text" name="penulis" placeholder="Penulis" required>
    <input type="text" name="penerbit" placeholder="Penerbit" required>
    <input type="number" name="jumlah_halaman" placeholder="Jumlah Halaman" required>
    <input type="number" name="stok" placeholder="stok" required>
    <input type="number" name="harga" placeholder="Harga" required>
    <label for="gambar_buku" style="text-align: left; font-weight: bold; color: #fff;">Pilih Gambar Buku:</label>
    <input type="file" name="gambar_buku" id="gambar_buku" accept="image/*" required> 
    <input type="submit" name="tambah" value="TAMBAH BUKU">
    </form>
</body>
</html>
