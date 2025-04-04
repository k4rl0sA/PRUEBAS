<head>
		<link href="../libs/css/menu.css" rel="stylesheet" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../libs/js/menu.js"></script>
	</head>
<?php
 session_start();
// require_once 'config.php';
ini_set('display_errors','1');
$vers='1.03.29.1';
 require_once __DIR__ . '../../02src/gestion.php';
 var_dump(session_status());
/* if (!isset($_SESSION['us_subred'])) {
    header("Location: /1/03public/loco.php");
    exit();
} 
// Verificar tiempo de inactividad
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    session_unset();
    session_destroy();
    header("Location: /1/03public/index.php");
    exit();
} */
$_SESSION['LAST_ACTIVITY'] = time(); // Actualizar tiempo de actividad
//require_once $_SERVER['DOCUMENT_ROOT'].'/1/02src/gestion.php';
  $sql="SELECT *
  FROM adm_menu
  WHERE id IN (
        SELECT m.id
  FROM adm_menu m 
    JOIN adm_menuusuarios mu ON m.id=mu.idmenu 
      JOIN usuarios u ON mu.perfil=u.perfil 
  WHERE  u.id_usuario = '".$_SESSION["us_subred"]."' AND m.estado='A' AND u.estado='A')
      OR menu IN (SELECT m.id
  FROM adm_menu m 
    JOIN adm_menuusuarios mu ON m.id=mu.idmenu 
      JOIN usuarios u ON mu.perfil=u.perfil 
  WHERE  u.id_usuario = '".$_SESSION["us_subred"]."' AND m.estado='A' AND u.estado='A') 
   ORDER BY `id`  ASC;";
$rtaMenu=datos_mysql($sql);
//  echo $sql;
// print_r($rtaMenu);
$sql1="SELECT nombre,perfil FROM usuarios WHERE id_usuario = '".$_SESSION["us_subred"]."'";
$rta=datos_mysql($sql1);
//print_r($rtaMenu);
$nav='';

$responseResult = $rtaMenu['responseResult'];
$menu = array();

foreach ($responseResult as $item) {
  // print_r($item);
  if (isset($item['menu']) && $item['menu'] == 0) {
      $menu[] = array(
          'id' => isset($item['id']) ? $item['id'] : null, 
          'text' => $item['link'],
          'icon' => $item['icono'],
          'link' => $item['enlace'] != '-' ? $item['enlace'] : 'javascript:void(0);',
          'submenu' => array()
      );
  }
}

//submenús 
foreach ($menu as &$mainMenuItem) {
  foreach ($responseResult as $item) {
      if (isset($item['menu']) && isset($mainMenuItem['id']) && $item['menu'] == $mainMenuItem['id']) {
          $mainMenuItem['submenu'][] = array(
              'text' => $item['link'],
              'icon' => $item['icono'],
              'link' => $item['enlace']
          );
      }
  }
}

/* foreach ($menu as &$mainMenuItem) {
  unset($mainMenuItem['id']);
} */

?>

<div class="sidebar close">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="../libs/img/Logo128.png" alt="logo">
            </span>
            <div class="text header-text">
                <span class="name">Control EBEH</span>
                <span class="profession">GITAPS</span>
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
                <?php foreach ($menu as $item): ?>
                    <li class="nav-link">
                        <a href="<?php echo $item['link']; ?>" class="main-item <?php echo isset($item['submenu']) ? 'has-submenu' : ''; ?>">
                            <i class="<?php echo $item['icon']; ?> icon"></i>
                            <span class="text nav-text"><?php echo $item['text']; ?></span>
                            <!-- </a> -->
                            <?php if (isset($item['submenu']) && $item['link'] === 'javascript:void(0);'): ?>
                                <i class="fa-solid fa-chevron-down submenu-arrow"></i>
                            <?php endif; ?>
                            </a>

                            <?php if (isset($item['submenu'])): ?>
                            <ul class="sub-menu">
                                <?php foreach ($item['submenu'] as $subitem): ?>
                                    <li class="nav-link">
                                        <a href="<?php echo $subitem['link']; ?>">
                                            <i class="<?php echo $subitem['icon']; ?> icon"></i>
                                            <span class="text nav-text"><?php echo $subitem['text']; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="bottom-content">
            <li>
                <a href="../../03public/logout.php">
                    <i class="fa-solid fa-arrow-right-from-bracket icon"></i>
                    <span class="text nav-text">Cerrar Sesión</span>
                </a>
            </li>
            <li class="mode">
                <div class="moon-sun">
                    <i class="fa fa-moon icon moon"></i>
                    <i class="fa fa-sun icon sun"></i>
                </div>
                <span class="mode-text text">Oscuro</span>
                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>
        </div>
    </div>
</div>

