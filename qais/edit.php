
<?php
session_start();
include("koneksi.php");

$data = []; // Inisialisasi $data untuk mencegah error jika buku belum ditemukan

// Jika parameter no_buku dikirim melalui URL (GET)
if(isset($_GET['no_buku'])){
    $id_to_edit = $_GET['no_buku'];
    $sql_select = "SELECT * FROM buku WHERE no_buku=$id_to_edit";
    $result_select = mysqli_query($koneksi,$sql_select);

    // Jika data buku ditemukan
    if($result_select && mysqli_num_rows($result_select) > 0){
        $data = mysqli_fetch_assoc($result_select);
    } else {
        // Jika tidak ditemukan, kembali ke halaman admin
        echo "<script>alert('Buku tidak ditemukan!'); window.location.href='admin.php';</script>";
        exit;
    }
}

// Jika tombol submit ditekan
if(isset($_POST['edit'])){
    $id = $_POST['no_buku_lama']; // Ambil ID lama dari input tersembunyi
    $kode_buku = $_POST['kode_buku'];
    $no_buku = $_POST['no_buku'];
    $judul_buku = $_POST['judul_buku'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $stok= $_POST['Stok'];
    $harga = $_POST['harga'];

    $gambar_buku_path = $_POST['gambar_buku_lama'] ?? ''; // Ambil path gambar lama

    // --- Logika Upload File ---
    if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] == 0) {
        $target_dir = "uploads/"; // Folder penyimpanan gambar
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Buat folder jika belum ada
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
            echo "<script>alert('File bukan gambar.');</script>";
            $uploadOk = 0;
        }

        // Batas ukuran file maksimal 5MB
        if ($_FILES["gambar_buku"]["size"] > 5000000) {
            echo "<script>alert('Ukuran file terlalu besar (Max 5MB).');</script>";
            $uploadOk = 0;
        }

        // Format file yang diizinkan
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "<script>alert('Hanya format JPG, JPEG, PNG & GIF yang diizinkan.');</script>";
            $uploadOk = 0;
        }

        // Jika semua validasi lolos, upload file
        if ($uploadOk == 0) {
            echo "<script>alert('Maaf, file Anda tidak terunggah.');</script>";
        } else {
            if (move_uploaded_file($_FILES["gambar_buku"]["tmp_name"], $target_file)) {
                // Jika file baru diunggah, hapus file lama (jika ada)
                if (!empty($gambar_buku_path) && file_exists($gambar_buku_path)) {
                    unlink($gambar_buku_path);
                }
                $gambar_buku_path = $target_file; // Update path ke gambar baru
            } else {
                echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file Anda.');</script>";
            }
        }
    }
    // --- Akhir Logika Upload File ---

    // Amankan data sebelum disimpan ke database
    $kode_buku = mysqli_real_escape_string($koneksi, $kode_buku);
    $judul_buku = mysqli_real_escape_string($koneksi, $judul_buku);
    $penulis = mysqli_real_escape_string($koneksi, $penulis);
    $penerbit = mysqli_real_escape_string($koneksi, $penerbit);

    // Query update data buku
    $sql_update = "UPDATE buku SET
        kode_buku='$kode_buku',
        no_buku=$no_buku,
        judul_buku='$judul_buku',
        tahun_terbit=$tahun_terbit,
        penulis='$penulis',
        penerbit='$penerbit',
        jumlah_halaman=$jumlah_halaman,
        Stok=$stok,
        harga=$harga,
        gambar_buku='$gambar_buku_path'
        WHERE no_buku=$id";

    // Eksekusi query
    if (mysqli_query($koneksi, $sql_update)) {
        echo "<script>alert('Buku berhasil diupdate!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($koneksi) . "');</script>";
    }
    exit; // Hentikan eksekusi setelah redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Buku</title>

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
    margin-bottom: 15px;
    border-radius: 6px;
    cursor: pointer;
    box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
    transition: background 0.3s, box-shadow 0.3s;
}

input[type="submit"]:hover {
    background-color: #1e90ff;
    box-shadow: 0 0 15px #1e90ff, 0 0 30px #00bfff;
}

.current-image-container {
    margin-top: 20px;
    text-align: center;
    color: #fff;
}

.current-image-container img {
    max-width: 100%;
    border-radius: 8px;
}

@keyframes glowing {
    0% { background-position: 0 0; }
    50% { background-position: 400% 0; }
    100% { background-position: 0 0; }
}
.current-image-container {
    margin-top: 20px;
    text-align: center;
    color: #fff;
}

.current-image-container img {
    width: 150px;       /* Ukuran gambar diperkecil */
    height: auto;       /* Menjaga rasio aspek gambar */
    border-radius: 8px;
    box-shadow: 0 0 10px #00bfff40;
    margin: 10px 0;
}


    </style>
</head>
<body>
    <form action="edit.php" method="post" enctype="multipart/form-data"> <h2>Edit Data Buku</h2>
        <input type="hidden" name="no_buku_lama" value="<?= htmlspecialchars($data['no_buku'] ?? '') ?>"> <input type="hidden" name="gambar_buku_lama" value="<?= htmlspecialchars($data['gambar_buku'] ?? '') ?>"> <input type="text" name="kode_buku" placeholder="Kode Buku" value="<?= htmlspecialchars($data['kode_buku'] ?? '') ?>" required>
        <input type="number" name="no_buku" placeholder="Nomor Buku" value="<?= htmlspecialchars($data['no_buku'] ?? '') ?>" required>
        <input type="text" name="judul_buku" placeholder="Judul Buku" value="<?= htmlspecialchars($data['judul_buku'] ?? '') ?>" required>
        <input type="number" name="tahun_terbit" placeholder="Tahun Terbit" value="<?= htmlspecialchars($data['tahun_terbit'] ?? '') ?>" required>
        <input type="text" name="penulis" placeholder="Penulis" value="<?= htmlspecialchars($data['penulis'] ?? '') ?>" required>
        <input type="text" name="penerbit" placeholder="Penerbit" value="<?= htmlspecialchars($data['penerbit'] ?? '') ?>" required>
        <input type="number" name="jumlah_halaman" placeholder="Jumlah Halaman" value="<?= htmlspecialchars($data['jumlah_halaman'] ?? '') ?>" required>
        <input type="number" name="Stok" placeholder="Stok" value="<?= htmlspecialchars($data['stok'] ?? '') ?>" required>
        <input type="number" name="harga" placeholder="Harga" value="<?= htmlspecialchars($data['harga'] ?? '') ?>" required>

        <label for="gambar_buku" style="text-align: left; font-weight: bold; color: #fff;">Gambar Buku:</label>
        <?php if (!empty($data['gambar_buku'])) { ?>
            <div class="current-image-container">
                <p>Gambar Saat Ini:</p>
                <img src="<?= htmlspecialchars($data['gambar_buku']) ?>" alt="Gambar Buku Saat Ini">
                <p>Pilih file baru untuk mengganti gambar.</p>
            </div>
        <?php } else { ?>
            <p style="text-align: center; color: #fff;">Belum ada gambar buku.</p>
        <?php } ?>

        <input type="file" name="gambar_buku" id="gambar_buku" accept="image/*"> <input type="submit" name="edit" value="UPDATE BUKU">

        <input type="submit" onclick="window.location.href='admin.php'" value="Kembali Ke Halaman">
    </form>
</body>
</html>
