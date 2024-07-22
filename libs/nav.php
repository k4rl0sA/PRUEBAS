<head>
		<link href="../libs/css/menu.css" rel="stylesheet" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../libs/js/app.js"></script>
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
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/gestion.php';
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
$total=count($rtaMenu['responseResult']);
foreach ($rtaMenu['responseResult'] as $key => $menu) {
  if($menu['tipo']=="MEN" && $menu['menu']==0 ){
    if($menu['contenedor']=="SI"){
      $nav.='<li class="nav-link"><div class="subnav"><button class="subnavbtn">';
    }else{
      //<li class="nav-link"><a href="#"><i class="fa-regular fa-rectangle-list icon"></i><span class="text nav-text">Item1</span></a></li>
      $nav.='<li class="nav-link"><a href="'.$menu['enlace'].'">';
    }  

    $nav.='<i class="'.$menu['icono'].' icon"></i><span class="text nav-text">'.$menu['link'].'</span>';
    // $nav.='<svg class="nav '.$menu['icono'].'"><use xlink:href="#'.$menu['icono'].'"/></svg>
    //     <br>'.$menu['link'].'</a>';
    if($menu['contenedor']=="SI"){
      $nav.='<i class="fa fa-caret-down"></i></button><div class="subnav-content">';
    }
    foreach ($rtaMenu['responseResult'] as $key => $item){
      if($item['tipo']=="SUB" && $item['menu']==$menu['id']){
        $nav.='<a href="'.$item['enlace'].'"
        class="eff-text-menu">'.$item['link'].'</a></li>';
      }
    }
    if($menu['contenedor']=="SI"){
      $nav.='</div></div>';
    }
  }elseif($menu['tipo']=="MEN" && $menu['menu']==''){
    $nav.='<a href="'.$menu['enlace'].'" class="eff-text-menu" >'.$menu['link'].'</a></li></div>';
  }
}
$nav.= '<div class="usuario">'.$rta['responseResult'][0]['nombre'].' - '.$rta['responseResult'][0]['perfil'].'_'.$vers.'</div>';
$nav.='</div><div class="bottom-content"><li><a href="../../logout.php"><i class="fa-solid fa-arrow-right-from-bracket icon"></i><span class="text nav-text">Cerrar Sesión</span>
        </a></li><li class="mode"><div class="moon-sun"><i class="fa fa-moon icon moon"></i><i class="fa fa-sun icon sun"></i></div><span class="mode-text text">Oscuro</span>
        <div class="toggle-switch"><span class="switch"></span></div></li></div></div></div>';
echo $nav;
?>




<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
<symbol id="home" viewBox="0 0 511.925 511.925"><title>home</title><g id="_x30_8_home"><path d="m267.073 66.622c-6.063-6.311-16.158-6.311-22.221 0l-171.2 178.213v223.149c0 12.479 10.117 22.596 22.596 22.596h319.428c12.479 0 22.596-10.117 22.596-22.596 0-4.604 0-212.422 0-223.15z" fill="#d8ecfe"/><path d="m195.021 490.58v-92.419c0-33.657 27.284-60.941 60.941-60.941 33.657 0 60.941 27.284 60.941 60.941v92.419c-6.397 0-114.897 0-121.882 0z" fill="#3c87d0"/><path d="m267.073 66.622c-6.063-6.311-16.158-6.311-22.221 0l-30.879 32.144c4.779-.733 9.836.767 13.456 4.536l171.2 178.213v209.066h17.047c12.479 0 22.596-10.117 22.596-22.596 0-4.604 0-212.422 0-223.15z" fill="#c4e2ff"/><path d="m244.852 94.968-199.373 207.54c-3.986 4.149-10.602 4.215-14.67.147l-20.339-20.339c-3.954-3.954-4.018-10.345-.144-14.378l230.822-240.278c8.084-8.415 21.544-8.415 29.628 0l230.822 240.278c3.874 4.033 3.81 10.424-.144 14.378l-20.339 20.339c-4.068 4.068-10.684 4.002-14.67-.147l-199.372-207.54c-6.063-6.311-16.159-6.311-22.221 0z" fill="#60b7ff"/></g><g id="_x30_8_home_2_"><path d="m430.772 276.2v191.784c0 8.324-6.771 15.096-15.096 15.096h-11.63c-4.143 0-7.5 3.357-7.5 7.5s3.357 7.5 7.5 7.5h11.63c16.595 0 30.096-13.501 30.096-30.096v-176.17l15.266 15.891c6.894 7.176 18.337 7.298 25.381.254l20.338-20.339c6.792-6.79 6.904-17.95.25-24.877l-230.822-240.279c-11.035-11.487-29.402-11.497-40.445 0l-230.823 240.279c-6.653 6.926-6.541 18.086.25 24.877l20.339 20.339c7.038 7.036 18.479 6.93 25.382-.254l15.264-15.889v36.432c0 4.143 3.358 7.5 7.5 7.5s7.5-3.357 7.5-7.5v-52.047l169.108-176.038c3.11-3.237 8.282-3.246 11.404 0 134.776 140.297 108.094 112.523 169.108 176.037zm65.378.812-20.339 20.34c-1.33 1.095-2.649 1.082-3.957-.04l-199.372-207.539c-.001-.001-.001-.001-.002-.002-.01-.011-.021-.02-.031-.031-9.023-9.358-23.99-9.354-33.006.032l-171.2 178.213c-.004.004-.007.009-.011.013l-28.161 29.314c-1.308 1.122-2.628 1.135-3.958.039l-20.339-20.339c-1.059-1.059-1.076-2.799-.039-3.878l230.822-240.279c5.139-5.349 13.674-5.348 18.811.001l230.821 240.277c1.038 1.08 1.021 2.821-.039 3.879z"/><path d="m368.897 483.079h-44.493v-84.919c0-37.738-30.703-68.44-68.442-68.44-37.738 0-68.441 30.702-68.441 68.44v84.919h-91.273c-8.324 0-15.096-6.771-15.096-15.096v-104.736c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v104.736c0 16.595 13.501 30.096 30.096 30.096h272.649c4.143 0 7.5-3.357 7.5-7.5s-3.357-7.5-7.5-7.5zm-166.376-84.918c0-29.467 23.974-53.44 53.441-53.44s53.441 23.974 53.441 53.44v84.919h-106.882z"/></g></symbol>
<symbol id="cerrar-sesion" viewBox="0 0 512.00533 512"><title>cerrar-sesion</title><path d="m298.667969 277.335938c-35.285157 0-64-28.714844-64-64 0-35.285157 28.714843-64 64-64h42.664062v-85.332032c0-35.285156-28.714843-63.99999975-64-63.99999975h-229.332031c-7.019531 0-13.589844 3.45312475-17.578125 9.23437475-3.96875 5.78125-4.863281 13.144531-2.347656 19.691407l154.667969 405.335937c3.136718 8.277344 11.070312 13.738281 19.925781 13.738281h74.664062c35.285157 0 64-28.714844 64-64v-106.667968zm0 0" fill="#2196f3"/><path d="m397.164062 318.382812c-7.957031-3.308593-13.164062-11.09375-13.164062-19.714843v-64h-85.332031c-11.777344 0-21.335938-9.554688-21.335938-21.332031 0-11.777344 9.558594-21.332032 21.335938-21.332032h85.332031v-64c0-8.621094 5.207031-16.40625 13.164062-19.714844 7.976563-3.304687 17.152344-1.46875 23.25 4.632813l85.335938 85.332031c8.339844 8.339844 8.339844 21.824219 0 30.164063l-85.335938 85.335937c-6.097656 6.097656-15.273437 7.933594-23.25 4.628906zm0 0" fill="#607d8b"/><path d="m184.449219 44.84375-128.191407-42.730469c-28.929687-8.894531-56.257812 12.460938-56.257812 40.554688v384c0 18.242187 11.605469 34.519531 28.886719 40.492187l128.167969 42.730469c4.714843 1.449219 9.046874 2.113281 13.613281 2.113281 23.53125 0 42.664062-19.136718 42.664062-42.667968v-384c0-18.238282-11.605469-34.515626-28.882812-40.492188zm0 0" fill="#64b5f6"/></symbol>
<!-- <symbol id="ok" viewBox="0 0 496.158 496.158"><title>OK</title><path style="fill:#32BEA6;" d="M496.158,248.085c0-137.021-111.07-248.082-248.076-248.082C111.07,0.003,0,111.063,0,248.085 c0,137.002,111.07,248.07,248.082,248.07C385.088,496.155,496.158,385.087,496.158,248.085z"/><path style="fill:#FFFFFF;" d="M384.673,164.968c-5.84-15.059-17.74-12.682-30.635-10.127c-7.701,1.605-41.953,11.631-96.148,68.777 c-22.49,23.717-37.326,42.625-47.094,57.045c-5.967-7.326-12.803-15.164-19.982-22.346c-22.078-22.072-46.699-37.23-47.734-37.867 c-10.332-6.316-23.82-3.066-30.154,7.258c-6.326,10.324-3.086,23.834,7.23,30.174c0.211,0.133,21.354,13.205,39.619,31.475 c18.627,18.629,35.504,43.822,35.67,44.066c4.109,6.178,11.008,9.783,18.266,9.783c1.246,0,2.504-0.105,3.756-0.322 c8.566-1.488,15.447-7.893,17.545-16.332c0.053-0.203,8.756-24.256,54.73-72.727c37.029-39.053,61.723-51.465,70.279-54.908 c0.082-0.014,0.141-0.02,0.252-0.043c-0.041,0.01,0.277-0.137,0.793-0.369c1.469-0.551,2.256-0.762,2.301-0.773 c-0.422,0.105-0.641,0.131-0.641,0.131l-0.014-0.076c3.959-1.727,11.371-4.916,11.533-4.984 C385.405,188.218,389.034,176.214,384.673,164.968z"/></symbol>
<symbol id="bad" viewBox="0 0 64 64"><title>Error</title><path d="m32 2a30 30 0 0 0 -24.863 46.792l-5.137 13.208 13.208-5.137a30 30 0 1 0 16.792-54.863z" fill="#a72b25"/><circle cx="32" cy="32" fill="#d23f34" r="22"/><g fill="#a72b25"><path d="m26.5 26.5a1 1 0 0 1 -.468-.116l-8.5-4.5.936-1.768 7.847 4.155 2.978-2.978 1.414 1.414-3.5 3.5a1 1 0 0 1 -.707.293z"/><path d="m38.5 26.5a1 1 0 0 1 -.707-.293l-3.5-3.5 1.414-1.414 2.978 2.978 7.847-4.155.936 1.768-8.5 4.5a1 1 0 0 1 -.468.116z"/><path d="m43 45h-2v-1a9 9 0 0 0 -18 0v1h-2v-1a11 11 0 0 1 22 0z"/></g></symbol> -->


