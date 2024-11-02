<?php
require_once 'auth.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['passwd'];

	/*
			$token=$_POST['token'];
			$url='https://www.google.com/recaptcha/api/siteverify';
			$req="$url?secret=$claves[privada]&response=$token";
			$rta=file_get_contents($req);
			$json=json_decode($rta,true);
			$ok=$json['success']; 
			if ($ok===false) {
				echo "<div class='error'>
					<span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
					<strong>Error!</strong> Error en el captcha, intentalo nuevamente.
					</div>";
				die();
			}
			if ($json['score']< 0.7) {
				echo "<div class='error'>
					<span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
					<strong>Error!</strong> Error en el captcha, No eres humano o que?
					</div>";
				die();
			}
	*/

    if (login($username, $password)) {
        $redirect = ($password === "riesgo2020+") ? "cambio-clave/" : "main/";
        header("Location: $redirect");
        exit();
    } else {
        echo "<div class='error'>
                <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
                <strong>Error!</strong> Usuario o contraseña incorrectos.
              </div>";
    }
}
include_once('./login/frmlogin.php');

?>