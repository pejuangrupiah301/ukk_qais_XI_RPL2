
<?php
session_start();
include('koneksi.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    

    <style>
   /* Reset dasar */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    background-color: #0a0a0a;
    color: #ffffff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 40px;
    text-align: center;
}

/* Judul Halaman */
h1 {
    color: #00bfff;
    margin-bottom: 30px;
    text-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
}

/* Tabel styling */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #111;
    box-shadow: 0 0 15px #00bfff33;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 30px;
}

thead th {
    background-color: #1e1e1e;
    color: #00bfff;
    padding: 12px;
    border-bottom: 2px solid #00bfff66;
}

tbody td {
    padding: 10px;
    border-bottom: 1px solid #333;
}

tbody tr:hover {
    background-color: #1a1a1a;
}

/* Gambar buku */
td img {
    width: 60px;
    height: auto;
    border-radius: 5px;
    box-shadow: 0 0 8px #00bfff88;
}

/* Tombol UPDATE dan DELETE */
a[href*="edit.php"],
a[href*="hapus.php"] {
    padding: 6px 10px;
    font-weight: bold;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: 0.3s;
    display: inline-block;
}

a[href*="edit.php"] {
    background-color: #00bfff;
    box-shadow: 0 0 8px #00bfff, 0 0 15px #1e90ff;
}

a[href*="edit.php"]:hover {
    background-color: #1e90ff;
    box-shadow: 0 0 10px #1e90ff, 0 0 20px #00bfff;
}

a[href*="hapus.php"] {
    background-color: #ff416c;
    box-shadow: 0 0 8px #ff416c, 0 0 15px #ff4b2b;
}

a[href*="hapus.php"]:hover {
    background-color: #ff4b2b;
    box-shadow: 0 0 10px #ff4b2b, 0 0 20px #ff416c;
}

/* Tombol Tambah Buku dan Logout */
input[type="submit"] {
    background-color: #00bfff;
    color: #fff;
    border: none;
    padding: 12px 24px;
    font-size: 15px;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    margin: 10px 5px;
    box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
    transition: background 0.3s, box-shadow 0.3s;
}

input[type="submit"]:hover {
    background-color: #1e90ff;
    box-shadow: 0 0 15px #1e90ff, 0 0 30px #00bfff;
}

</style>
  
</head>
<body>
   <center><h1>DATA BUKU</h1></center> 

    <table border="1"> <thead>
            <tr>
                <th>kode buku</th>
                <th>no buku</th>
                <th>judul buku</th>
                <th>tahun terbit</th>
                <th>penulis</th>
                <th>penerbit</th>
                <th>jumlah halaman</th>
                <th>stok</th>
                <th>harga</th>
                <th>gambar buku</th>
                <th>UPDATE</th>
                <th>DELETE</th>
            </tr>
        </thead>
        
        <tbody>
        <?php
            $sql="SELECT * FROM buku";
            $row=mysqli_query($koneksi,$sql);
            while ($data = mysqli_fetch_array($row)){
        ?>
            <tr>
                <td><?= htmlspecialchars($data ['kode_buku']) ?></td>
                <td><?= htmlspecialchars($data ['no_buku']) ?></td>
                <td><?= htmlspecialchars($data ['judul_buku']) ?></td>
                <td><?= htmlspecialchars($data ['tahun_terbit']) ?></td>
                <td><?= htmlspecialchars($data ['penulis']) ?></td>
                <td><?= htmlspecialchars($data ['penerbit']) ?></td>
                <td><?= htmlspecialchars($data ['jumlah_halaman']) ?></td>
                <td><?= htmlspecialchars($data ['stok']) ?></td>
                <td><?= htmlspecialchars($data ['harga']) ?></td>
                <td>
                    <?php if ($data['gambar_buku']) { ?>
                        <img src="<?= htmlspecialchars($data['gambar_buku']) ?>" alt="Gambar Buku">
                    <?php } else {
                        echo "Tidak ada gambar";
                    } ?>
                </td>
                <td>
                    <a href="edit.php?no_buku=<?= htmlspecialchars($data['no_buku']) ?>">UPDATE</a>
                </td>

                <td>
                    <a href="hapus.php?no_buku=<?= htmlspecialchars($data['no_buku']) ?>">DELETE</a>
            </td>
          

            </tr>
        <?php
        }
        ?>
        </tbody>
      </table>
      <input type="submit" onclick="window.location.href='tambah_buku.php'" value="Tambah Daftar Buku">
      <input type="submit" onclick="window.location.href='login.php'" value="LOGOUT">
</body>
</html>
