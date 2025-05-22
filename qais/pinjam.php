
<?php
session_start();
include("koneksi.php");

$message = ""; // Untuk menyimpan pesan

if (isset($_GET['kode_buku'])) {
    $kode_buku = mysqli_real_escape_string($koneksi, $_GET['kode_buku']);

    // Ambil data buku
    $sql_book = "SELECT * FROM buku WHERE kode_buku = '$kode_buku'";
    $result_book = mysqli_query($koneksi, $sql_book);
    $book_data = mysqli_fetch_array($result_book);

    if (!$book_data) {
        $message = "Buku tidak ditemukan.";
    }

    // Proses peminjaman buku
    if (isset($_POST['submit_pinjam']) && $book_data['stok'] > 0) {
        $nama_peminjam = mysqli_real_escape_string($koneksi, $_POST['nama_peminjam']);
        $tanggal_pinjam = date('Y-m-d');
        $tanggal_kembali_aktual = date('Y-m-d', strtotime('+7 days'));

       $sql_insert = "INSERT INTO peminjaman (
    kode_buku, nama_peminjam, tanggal_pinjam, tanggal_kembali_aktual, status, tanggal_dikembalikan
) VALUES (
    '$kode_buku', '$nama_peminjam', '$tanggal_pinjam', '$tanggal_kembali_aktual', 'dipinjam', NULL
)";

        if (mysqli_query($koneksi, $sql_insert)) {
            $sql_update_stok = "UPDATE buku SET stok = stok - 1 WHERE kode_buku = '$kode_buku'";
            mysqli_query($koneksi, $sql_update_stok);

            $message = "Buku berhasil dipinjam oleh " . $nama_peminjam . ".";
            header("Location: index.php");
            exit();
        } else {
            $message = "Error: " . mysqli_error($koneksi);
        }
    }

    // Proses menambahkan stok buku
    if (isset($_POST['tambah_stok'])) {
        $jumlah_tambah = intval($_POST['jumlah_stok']);
        if ($jumlah_tambah > 0) {
            $sql_update_stok = "UPDATE buku SET stok = stok + $jumlah_tambah WHERE kode_buku = '$kode_buku'";
            if (mysqli_query($koneksi, $sql_update_stok)) {
                $message = "Stok berhasil ditambahkan sebanyak $jumlah_tambah.";
                // Refresh halaman untuk menampilkan stok terbaru
                header("Location: pinjam.php?kode_buku=$kode_buku");
                exit();
            } else {
                $message = "Error saat menambahkan stok: " . mysqli_error($koneksi);
            }
        } else {
            $message = "Jumlah stok harus lebih dari 0.";
        }
    }
} else {
    $message = "Kode buku tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
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
    padding: 50px;
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

h1 {
    text-align: center;
    color: #00bfff;
    margin-bottom: 20px;
    text-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
}

.message {
    text-align: center;
    padding: 10px;
    background-color: #111;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 16px;
    color: #fff;
}

.message.error-message {
    background-color: #e74c3c;
}

.book-details p {
    margin: 10px 0;
}

input[type="text"],
input[type="submit"] {
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
input[type="submit"]:focus {
    background-color: #151515;
    border-bottom: 2px solid #1e90ff;
    color: #00bfff;
    box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
}

input[type="submit"] {
    background-color: #00bfff;
    color: white;
    font-weight: bold;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
    transition: background 0.3s, box-shadow 0.3s;
}

input[type="submit"]:hover {
    background-color: #1e90ff;
    box-shadow: 0 0 15px #1e90ff, 0 0 30px #00bfff;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    color: #00bfff;
    font-weight: bold;
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
        <h1>Pinjam Buku</h1>
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'Error') !== false ? 'error-message' : '' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (isset($book_data)): ?>
            <div class="book-details">
                <p><strong>Kode Buku:</strong> <?= htmlspecialchars($book_data['kode_buku']) ?></p>
                <p><strong>Judul Buku:</strong> <?= htmlspecialchars($book_data['judul_buku']) ?></p>
                <p><strong>Penulis:</strong> <?= htmlspecialchars($book_data['penulis']) ?></p>
                <p><strong>Stok Tersedia:</strong> <?= htmlspecialchars($book_data['stok']) ?></p>
            </div>

            <?php if ($book_data['stok'] > 0): ?>
                <form action="" method="POST">
                    <label for="nama_peminjam">Nama Peminjam:</label>
                    <input type="text" id="nama_peminjam" name="nama_peminjam" required>
                    <input type="submit" name="submit_pinjam" value="Konfirmasi Pinjam">
                </form>
            <?php else: ?>
                <p style="color: red; font-weight: bold;">Stok buku habis. Tidak bisa dipinjam.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Silakan pilih buku dari daftar untuk dipinjam.</p>
        <?php endif; ?>

        <a href="index.php" class="back-link">Kembali ke Halaman</a>
    </div>
</body>
</html>
