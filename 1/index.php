<?php
session_start();
ini_set('display_errors', '1');
include_once('./login/login.php');
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$name = test_input($_POST['user']);
			$pwd =$_POST['pwd'];

			//RECAPTCHA
			$token=$_POST['tkn'];
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
			//RECAPTCHA
			$valida=login($name,$pwd);
			if ($valida === true){
				$_SESSION["us_subred"] = strtolower($name);
				if($_POST["pwd"] == "Subred2025+"){
					$link="cambio-clave/";
					echo "<script>window.location.replace('".$link."');</script>";
				}else{
					$link="Inicio/";
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
				$dominio = $_SERVER['HTTP_HOST'];
			$comy = array(
			  'controlebeh.site' => [
				  's' => 'localhost',
				  'u' => 'u470700275_17',
				  'p' => 'z9#KqH!YK2VEyJpT',
				  'bd' => 'u470700275_17'
			  ],
			  'produccion.site' => [
				  's' => 'localhost',
				  'u' => 'u470700275_08',
				  'p' => 'z9#KqH!YK2VEyJpT',
				  'bd' => 'u470700275_08'
			  ]
			);
				if (array_key_exists($dominio, $comy)) {
				  $dbConfig = $comy[$dominio];
				} else {
					echo($dominio);
				  die('Dominio no reconocido.');
				}
				$con = new mysqli($dbConfig['s'], $dbConfig['u'],$dbConfig['p'],$dbConfig['bd']);
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
				$_SESSION['us_subred']=$id_usuario;
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