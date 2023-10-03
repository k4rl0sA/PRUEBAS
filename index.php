<?php
session_start();
// require_once '../libs/config.php';
ini_set('display_errors', '1');
include_once('./login/frmlogin.php');
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$name = test_input($_POST['username']);
			$pwd =$_POST['passwd'];
			/*********************************************  PARA PRODUCCION
			
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
			$valida=login($name,$pwd);
			if ($valida === true){
				$_SESSION["us_sds"] = strtolower($name);
				$_SESSION['expira'] = time() + 120*60;
				if($_POST["passwd"] == "riesgo2020+"){
					$link="cambio-clave/";
					// echo "<script>alert('".$valida."  -  ".$link."');</script>";
					echo "<script>window.location.replace('".$link."');</script>";
				}else{
					$link="main/";
					echo "<script>window.location.replace('".$link."');</script>";
				}
			}else{
				echo "<div class='error'>
					<span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
					<strong>Error!</strong> Vaya, no hemos encontrado nada que coincida con este nombre de usuario y contraseña en nuestra base de datos.
					</div>";
					die();
				}
			}

function db_connect(){
		//$con = new mysqli("srv247.hstgr.io", "u470700275_06", "z9#KqH!YK2VEyJpT","u470700275_06");
		//$con = new mysqli("srv247.hstgr.io", "u470700275_07", "z9#KqH!YK2VEyJpT","u470700275_07");
		$con = new mysqli("localhost", "u470700275_06", "z9#KqH!YK2VEyJpT","u470700275_06");
  if( !$con ){
    throw new Exception('No se ha podido conectar a la base de datos');
	die();
  } else {
    return $con;
  }
}

function login($username,$password){
	$con = db_connect();
	$name = filter_var($username, FILTER_SANITIZE_STRING);
    $pwd = filter_var($password, FILTER_SANITIZE_STRING);
    if(!($rta = $con->prepare("SELECT id_usuario,nombre,clave FROM usuarios where id_usuario =? AND estado='A'"))){
		echo "Prepare failed: (" . $con->errno . ")" . $con->error;
        exit();}
		if(!$rta->bind_param('s', $name)){
			  echo "Bind failed: (" . $rta->errno . ")" . $rta->error;
			  exit();}
			if(!$rta->execute()){
				echo "Execute failed: (" . $rta->errno .")" . $rta->error;
				exit();}
		$rta->bind_result($id_usuario,$nombre,$clave);
		$rta->store_result();
		$count = $rta->num_rows;
        $rta->fetch();
            if(password_verify($pwd, $clave)){
				$_SESSION['us_sds']=$id_usuario;
				$_SESSION['nomb']=$nombre;
			return true;
			}else{
				return false;
			}
			// $rta->free();
	$con->close();
}	 
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
