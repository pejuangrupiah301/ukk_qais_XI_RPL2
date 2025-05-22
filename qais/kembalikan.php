
<?php
session_start();
include("koneksi.php");

$message = ""; // Untuk menyimpan pesan sukses atau error

// Mengecek apakah parameter kode_buku tersedia di URL
if (isset($_GET['kode_buku'])) {
    // Menghindari SQL Injection dengan membersihkan input
    $kode_buku = mysqli_real_escape_string($koneksi, $_GET['kode_buku']);

    // Mengambil data peminjaman yang masih aktif (belum dikembalikan) untuk buku ini
    $sql_borrowed = "SELECT * FROM peminjaman WHERE kode_buku = '$kode_buku' AND status = 'dipinjam'";
    $result_borrowed = mysqli_query($koneksi, $sql_borrowed);
    $borrowed_books = mysqli_fetch_all($result_borrowed, MYSQLI_ASSOC);

    // Jika tidak ada peminjaman aktif
    if (empty($borrowed_books)) {
        $message = "Tidak ada riwayat peminjaman aktif untuk buku ini.";
    }

    // Jika form pengembalian dikirim
    if (isset($_POST['submit_kembali'])) {
        // Mengambil ID peminjaman dari form
        $id_peminjaman = mysqli_real_escape_string($koneksi, $_POST['id_peminjaman']);
        $tanggal_kembali_aktual = date('Y-m-d'); // Tanggal hari ini

        // Mengubah status peminjaman menjadi 'dikembalikan' dan mencatat tanggal kembali
        $sql_update = "UPDATE peminjaman 
                       SET status = 'dikembalikan', tanggal_kembali_aktual = '$tanggal_kembali_aktual' 
                       WHERE id_peminjaman = '$id_peminjaman'";

                       

        // Mengeksekusi query update
        if (mysqli_query($koneksi, $sql_update)) {
    // Tambah stok buku kembali
    $sql_update_stok = "UPDATE buku SET stok = stok + 1 WHERE kode_buku = '$kode_buku'";
    mysqli_query($koneksi, $sql_update_stok);

    $message = "Buku berhasil dikembalikan dan stok ditambahkan.";
    
    header("Location: index.php");
    exit();


        } else {
            // Jika terjadi kesalahan saat query update
            $message = "Error: " . mysqli_error($koneksi);
        }
    }
} else {
    // Jika kode buku tidak dikirim melalui URL
    $message = "Kode buku tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kembalikan Buku</title>
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

.container {
    background-color: #0a0a0a;
    padding: 40px;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 0 20px #00bfff50, 0 0 40px #00bfff20, inset 0 0 10px #00bfff20;
    position: relative;
}

.container::before {
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

.container h1 {
    text-align: center;
    color: #00bfff;
    margin-bottom: 20px;
    text-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
}

.message {
    padding: 10px;
    margin-bottom: 20px;
    background-color: #1e90ff;
    color: #fff;
    font-weight: bold;
    text-align: center;
    border-radius: 8px;
    box-shadow: 0 0 10px #00bfff;
}

.error-message {
    background-color: red;
}

.borrowed-list {
    margin-top: 20px;
}

.borrowed-item {
    background-color: #111;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 0 10px #00bfff;
}

.borrowed-item span {
    color: #00bfff;
    font-weight: bold;
}

.borrowed-item button {
    background-color: #00bfff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 0 10px #00bfff;
    transition: background 0.3s, box-shadow 0.3s;
}

.borrowed-item button:hover {
    background-color: #1e90ff;
    box-shadow: 0 0 15px #1e90ff, 0 0 30px #00bfff;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #00bfff;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    box-shadow: 0 0 10px #00bfff;
    padding: 10px;
    border-radius: 6px;
    transition: background 0.3s, box-shadow 0.3s;
}

.back-link:hover {
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
    <div class="container">
        <h1>Kembalikan Buku</h1>
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'Error') !== false ? 'error-message' : '' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (isset($borrowed_books) && !empty($borrowed_books)): ?>
            <div class="borrowed-list">
                <h3>Riwayat Peminjaman Aktif untuk Buku Ini:</h3>
                <?php foreach ($borrowed_books as $borrow): ?>
                    <div class="borrowed-item">
                        <span><strong>Peminjam:</strong> <?= htmlspecialchars($borrow['nama_peminjam']) ?> | <strong>Tanggal Pinjam:</strong> <?= htmlspecialchars($borrow['tanggal_pinjam']) ?></span>
                        <form action="" method="POST">
                            <input type="hidden" name="id_peminjaman" value="<?= $borrow['id_peminjaman'] ?>">
                            <button type="submit" name="submit_kembali">Kembalikan Ini</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada buku ini yang sedang dipinjam saat ini.</p>
        <?php endif; ?>
        <a href="index.php" class="back-link">Kembali ke Halaman</a>
    </div>
</body>
</html>
