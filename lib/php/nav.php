<head>
		<link href="../lib/css/menu.css" rel="stylesheet" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../lib/js/app.js"></script>
	</head>

  <div class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="../libs/img/Logo128.png" alt="logo">
                </span>
                <div class="text header-text">
                    <span class="name">Secretaria de Salud</span>
                    <span class="profession">SIGINF</span>
                </div>
            </div>
            <i class="fa-solid fa-angle-right toggle"></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <li class="search-box">
                    <i class="fa-solid fa-magnifying-glass icon"></i>
                    <input id="search" type="search" placeholder="Buscar . . .">
                </li>
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="javascript:void(0);" class="main-item">
                            <i class="fa-solid fa-flag icon"></i>
                            <span class="text nav-text">Inicio</span>
                        </a>
<?php
// require_once 'config.php';
ini_set('display_errors','1');
$vers='1.03.29.1';
// if (!isset($_SESSION["us_riesgo"])){ die("<script>window.top.location.href = '/';</script>");}
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/php/gestion.php';
  $sql="SELECT *
  FROM adm_menu
  WHERE id IN (
        SELECT m.id
  FROM adm_menu m 
    JOIN adm_menuusuarios mu ON m.id=mu.idmenu 
      JOIN usuarios u ON mu.perfil=u.perfil 
  WHERE  u.id_usuario = '".$_SESSION["us_sds"]."' AND m.estado='A' AND u.estado='A')
      OR menu IN (SELECT m.id
  FROM adm_menu m 
    JOIN adm_menuusuarios mu ON m.id=mu.idmenu 
      JOIN usuarios u ON mu.perfil=u.perfil 
  WHERE  u.id_usuario = '".$_SESSION["us_sds"]."' AND m.estado='A' AND u.estado='A') 
   ORDER BY `id`  ASC;";
$rtaMenu=datos_mysql($sql);
// echo $sql;
// print_r($rtaMenu);
$sql1="SELECT nombre,perfil FROM usuarios WHERE id_usuario = '".$_SESSION["us_sds"]."'";
$rta=datos_mysql($sql1);
$nav='';
foreach ($rtaMenu['responseResult'] as $menu) {
  if ($menu['tipo'] == "MEN" && $menu['menu'] == 0) {
      $nav .= '<li class="nav-link">';
      if ($menu['contenedor'] == "SI") {
          $nav .= '<a href="javascript:void(0);" class="main-item">';
      } else {
          $nav .= '<a href="' . $menu['enlace'] . '">';
      }
      $nav .= '<i class="' . $menu['icono'] . ' icon"></i><span class="text nav-text">' . $menu['link'] . '</span>';
      // ...
  }
}
$nav .= '<div class="usuario">' . $rta['responseResult'][0]['nombre'] . ' - ' . $rta['responseResult'][0]['perfil'] . '_' . $vers . '</div>';
$nav .= '<div class="bottom-content">';
$nav .= '<li><a href="../../logout.php"><i class="fa-solid fa-arrow-right-from-bracket icon"></i><span class="text nav-text">Cerrar Sesión</span></a></li>';
$nav .= '<li class="mode"><div class="moon-sun"><i class="fa fa-moon icon moon"></i><i class="fa fa-sun icon sun"></i></div><span class="mode-text text">Oscuro</span>';
$nav .= '<div class="toggle-switch"><span class="switch"></span></div></li>';
$nav .= '</div>';
echo $nav;
?>


