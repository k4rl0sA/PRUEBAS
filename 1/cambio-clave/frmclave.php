<!DOCTYPE html>
<html>
<head>
	<title>Cambio de Clave || Proteger</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link rel="stylesheet" href="../libs/css/styleLogin.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sirin+Stencil&display=swap" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="login,secretaria de salud,salud,subred,servicios,sur,norte,eac,occidente,medicina" />
<script>
	function getUser(a){
		var user=document.getElementById('user'); 
		user.value=a;
	}
</script>
</head>
<body>
<body onload="getUser('<?php echo $_SESSION['us_subred'];?>');">
    <section class="main">
        <div class="login-container">
            <p class="title">CAMBIO DE CONTRASEÑA</p>
            <div class="separator"></div>
            <form class="login-form" method="POST">
                    <input type="text" name="username" id='user' class="form-control" placeholder="USUARIO" required="required">
				<div class="form-control">
					<input type="password" name="passwd" class="form-control" autocomplete="off" placeholder="CONTRASEÑA NUEVA" required="required" >
					<i class="fas fa-lock"></i>
				</div>
                <div class="form-control">
                    <input type="password" name="repasswd" class="form-control" placeholder="CONFIRMAR CONTRASEÑA" required="required">
                    <i class="fas fa-lock"></i>
                </div>
                <button class="submit">Cambiar Clave</button>
            </form>
        </div>
    </section>
	<section class="side">
        <img src="../libs/img/SDS.png" alt="">
    </section>	
<body>
</html>
