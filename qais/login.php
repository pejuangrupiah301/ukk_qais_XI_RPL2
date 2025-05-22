
<?php
session_start();
include('koneksi.php');

    if (isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        

        $sql = "SELECT * FROM  user WHERE username='$username' AND password='$password'";
        $result = mysqli_query($koneksi,$sql);

        if($result ->num_rows > 0){
            $data = mysqli_fetch_assoc($result);

            if($data ['role'] == "user"){
                header('location: index.php');
            }
            else if ($data['role'] == "admin"){
                header('location: admin.php');
            }
            else{
                header('location: superadmin.php');
            }
        }else{
            echo ' LOGIN GAGAL';
        }


    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>

    <style>
       /* Reset dasar */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body dengan latar hitam dan efek gradien biru */
body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #000000;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    overflow: hidden;
}

/* Container box */
.box {
    background: #0a0a0a;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 0 15px #00bfff50, 0 0 30px #00bfff20, inset 0 0 10px #00bfff20;
    width: 100%;
    max-width: 350px;
    position: relative;
}

/* Animasi cahaya biru melingkar */
.box::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #00bfff, #1e90ff, #00bfff, #1e90ff);
    z-index: -1;
    background-size: 400%;
    filter: blur(8px);
    animation: glowing 20s linear infinite;
    border-radius: 14px;
}

/* Input style */
.box input[type="text"],
.box input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    background-color: #111;
    border: none;
    border-bottom: 2px solid #00bfff;
    color: #00bfff;
    font-size: 15px;
    outline: none;
    transition: 0.3s;
    box-shadow: 0 0 5px rgba(0, 191, 255, 0.2);
}

/* Efek saat input difokuskan */
.box input[type="text"]:focus,
.box input[type="password"]:focus {
    border-bottom: 2px solid #1e90ff;
    background-color: #151515;
    color: #00bfff;
    box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
}

/* Efek glow saat sudah diisi */
.box input[type="text"]:not(:placeholder-shown),
.box input[type="password"]:not(:placeholder-shown) {
    color: #00bfff;
    box-shadow: 0 0 8px #00bfff, 0 0 12px #1e90ff;
}

/* Tombol login */
.box input[type="submit"] {
    width: 100%;
    padding: 12px;
    background: #00bfff;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
    transition: 0.3s;
}

.box input[type="submit"]:hover {
    background: #1e90ff;
    box-shadow: 0 0 15px #1e90ff, 0 0 30px #00bfff;
}

/* Animasi cahaya neon */
@keyframes glowing {
    0% { background-position: 0 0; }
    50% { background-position: 400% 0; }
    100% { background-position: 0 0; }
}

    </style>


</head>
<body>
    <div class="box">
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Input Username" required>
            <input type="password" name="password" placeholder="Input password" required>
            <input type="submit" name="login" value="LOGIN">
        </form>
    </div>
</body>
</html>
