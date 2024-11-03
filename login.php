<?php

function getIPAddress() {
    $ipAddress = 'undefined';

    if (isset($_SERVER)) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        }
    } else {
        $ipAddress = getenv('REMOTE_ADDR');

        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $ipAddress = getenv('HTTP_CLIENT_IP');
        }
    }

    $ipAddress = htmlspecialchars($ipAddress, ENT_QUOTES, 'UTF-8');
    return $ipAddress;
}

session_start();
$jsondata111 = file_get_contents("./includes/ansibo.json");
$json111 = json_decode($jsondata111, true);
$col1 = $json111["info"];
$col2 = $col1["aa"];
$db_check1 = new SQLite3("api/.anspanel.db");
$db_check1->exec("CREATE TABLE IF NOT EXISTS USERS(id INT PRIMARY KEY, NAME TEXT, USERNAME TEXT, PASSWORD TEXT, LOGO TEXT)");

$rows = $db_check1->query("SELECT COUNT(*) as count FROM USERS");
$row = $rows->fetchArray();
$numRows = $row["count"];

if ($numRows == 0) {
    $db_check1->exec("INSERT INTO USERS(id, NAME, USERNAME, PASSWORD, LOGO) VALUES('1','Seu Nome','admin','admin','img/logo.png')");
}

$res_login = $db_check1->query("SELECT * FROM USERS WHERE id='1'");
$row_login = $res_login->fetchArray();
$name_login = $row_login["NAME"];
$logo_login = $row_login["LOGO"];

if (isset($_POST["login"])) {
    if (!$db_check1) {
        echo $db_check1->lastErrorMsg();
    }

    $sql_check = "SELECT * FROM USERS WHERE USERNAME='" . $_POST["username"] . "' AND PASSWORD='" . $_POST["password"] . "'";
    $ret_check = $db_check1->query($sql_check);

    while ($row_check = $ret_check->fetchArray()) {
        $id_check = $row_check["id"];
        $store_type = $row_check["store_type"];
        $NAME = $row_check["NAME"];
        $LOGO_check = $row_check["LOGO"];
        $isAdmin = $row_check['ADMIN'];
    }

    if (empty($id_check)) {
        $message = "<div class=\"alert alert-danger\" id=\"flash-msg\"><h4><i class=\"icon fa fa-times\"></i>Usuário ou senha inválidos!</h4></div>";
        echo $message;
    } else {
        $_SESSION["admin"] = $isAdmin;
        $_SESSION["N"] = $id_check;
        $_SESSION["id"] = $id_check;
        $_SESSION["store_type"] = $store_type;

        $path = "users";
        if ($store_type == '2') {
            $path .= '_mac';
        }

        header("Location: $path.php");
    }

    $db_check1->close();
}

$date = date("d-m-Y H:i:s");
$IPADDRESS = getIPAddress();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VU Player ATV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="css/sb-admin-<?php echo $col2; ?>.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        @media (max-width: 767px) {
            body {
                padding-top: 40px;
                background-color: black;
                color: white;
            }
        }

        body {
            font-family: 'Roboto', sans-serif;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
        }

        .password-toggle-icon {
            cursor: pointer;
            user-select: none;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="form-container">
            <form method="POST">
                <p class="text" style="transition-delay: 0.4s"><br>
                </p>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="NOME DE USUÁRIO" name="username" required autofocus />
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="SENHA" name="password" required />
                        <button type="button" class="btn btn-secondary password-toggle-icon" onclick="togglePasswordVisibility()">Mostrar</button>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-lg btn btn-primary btn-block" name="login" type="submit">Login</button>
                </div>
                <p class="text-center text-warning"></p><br>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-easing@1.4.1/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.querySelector('[name="password"]');
            const passwordIcon = document.querySelector('.password-toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.textContent = 'Ocultar';
            } else {
                passwordInput.type = 'password';
                passwordIcon.textContent = 'Mostrar';
            }
        }
    </script>
</body>

</html>
