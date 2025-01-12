<?php
session_start(); 
include ('../01config/claves.php');
require_once __DIR__ . '/../../02src/gestion.php'; 

function login($username, $password) {
    $con = db_connect();
    $stmt = $con->prepare("SELECT id_usuario, nombre, clave FROM usuarios WHERE id_usuario = ? AND estado = 'A'");
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $con->error);
    }

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $nombre, $clave);
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->fetch();
        if (password_verify($password, $clave)) {
            $_SESSION['us_subred'] = $id_usuario;
            $_SESSION['nomb'] = $nombre;
            return true;
        }
    }
    return false;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>CONTROL EBEH</title>
<link href="./libs/css/styleLogin.css" rel="stylesheet" type="text/css" media="all"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="keywords" content="login,IPS,salud,proteger,servicios,vacunas,medicina,ips" />
<link href='//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $claves['publica'];?>"></script>
</head>
<body>
<div class="login">
    <div class="login-top">
        <img src="../libs/img/p.png">
    </div>
    <h1>Inicio</h1>
    <form action="#" method="post" class="frm">
        <div class="input">
            <input type="text" name="user" required>
            <label>Usuario</label>
        </div>
        <div class="input">
            <input type="password" name="pwd" required>
            <label>Contraseña</label>
        </div>
        <input type="hidden" id="tkn" name="tkn">
        <button id='btn' class="btn" disabled>
            <span class="text">Ingresar</span>
                <i class="fas fa-sign-in-alt icon"></i>
        </button>
    </form>
    <p>Olvido su contraseña?<a href="#"> Click Aquí </a></p>
</div>
<script>
        grecaptcha.ready(function(){
            grecaptcha.execute(
                '<?php echo $claves['publica'];?>',
                {action:'formulario'}
            ).then(function(rta_token){
                const tkn=document.getElementById('tkn');
                const btn=document.getElementById('btn');
                tkn.value=rta_token;
                btn.disabled=false;
            })
        });
    </script>
</body>
</html>
