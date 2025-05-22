
<?php
    include("koneksi.php");
    if (isset($_GET['no_buku'])){
        $no_buku = $_GET['no_buku'];
        mysqli_query($koneksi, "DELETE FROM buku WHERE no_buku='$no_buku'");
    }
    header("location: admin.php");
    exit();
?>
