
<?php
    session_start();
    include('koneksi.php');

    if(isset($_POST['tambah'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        mysqli_query($koneksi,"INSERT INTO user(username, password, role)
        VALUES ('$username', '$password', '$role')");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUPERADMIN</title>


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
        color: #fff;
    }

    form {
        background-color: #0a0a0a;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 0 15px #00bfff50, 0 0 30px #00bfff20, inset 0 0 10px #00bfff20;
        width: 100%;
        max-width: 400px;
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

    input[type="text"],
    select {
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

    input[type="text"]:focus,
    select:focus {
        border-bottom: 2px solid #1e90ff;
        background-color: #151515;
        color: #00bfff;
        box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
    }

    input[type="text"]:not(:placeholder-shown),
    select:valid {
        box-shadow: 0 0 8px #00bfff, 0 0 12px #1e90ff;
    }

    input[type="submit"] {
        width: 48%;
        padding: 12px;
        background-color: #00bfff;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin: 12px 1%;
        box-shadow: 0 0 10px #00bfff, 0 0 20px #1e90ff;
        transition: 0.3s;
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
    <form action="superadmin.php" method="post">
        <input type="text" name="username" placeholder="username" required>
        <input type="text" name="password" placeholder="password" required>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="superadmin">Superadmin</option>
        </select>        
        <input type="submit" name="tambah" value="Simpan">
        <input type="submit" onclick="window.location.href='login.php'" value="Login">
    
        
        
    </form>
</body>
</html>
