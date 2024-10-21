<?php
session_start();
ini_set('display_errors','1');
setlocale(LC_TIME, 'es_CO');
// $GLOBALS['app']='sds';
ini_set('memory_limit','1024M');
date_default_timezone_set('America/Bogota');
setlocale(LC_ALL,'es_CO');
if (!isset($_SESSION["us_sds"])) {
  header("Location: /index.php"); 
  exit;
}
$ruta_upload='/public_html/upload/';
$env='prod';
$comy=array('prod' => ['s'=>'localhost','u' => 'u470700275_17','p' => 'z9#KqH!YK2VEyJpT','bd' => 'u470700275_17']);
$con=mysqli_connect($comy[$env]['s'],$comy[$env]['u'],$comy[$env]['p'],$comy[$env]['bd']);//."<script>window.top.location.href='/';</script>");
if (!$con) { $error = mysqli_connect_error();  exit; }
mysqli_set_charset($con,"utf8");
$GLOBALS['con']=$con;
// $cv=array(isset($_SESSION["us_{$GLOBALS['app']}"])?$_SESSION["us_{$GLOBALS['app']}"]:"","NOW()");
// $SESSION['perfil']=datos_mysql("SELECT nombre,perfil FROM usuarios WHERE id_usuario='".$_SESSION["us_{$GLOBALS['app']}"]."'");
//$cabecera = "<html><head><link rel='stylesheet' href='s.css' type='text/css' media='screen'><script src='js/c.js'></script></head>";
$req = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
//~ var_dump($req);
// var_dump($con);
switch ($req) {
	case '';
	break;
	case 'exportar':
    $now=date("ymd");
		header_csv($_REQUEST['b'] .'_'.$now.'.csv');
    $info=datos_mysql($_SESSION['tot_' . $_REQUEST['b']]);
		$total=$info['responseResult'][0]['total'];
		if ($rs = mysqli_query($GLOBALS[isset($_REQUEST['con']) ? $_REQUEST['con'] : 'con'], $_SESSION['sql_' . $_REQUEST['b']])) {
			$ts = mysqli_fetch_array($rs, MYSQLI_ASSOC);
			echo csv($ts, $rs,$total);
		} else {
			echo "Error " . $GLOBALS['con']->errno . ": " . $GLOBALS['con']->error;
      $GLOBALS['con']->close();
		}
		die;
		break;
	case 'upload':
		$cr = $_REQUEST['c'];
		$ya = new DateTime();
		$tb = $_POST['b'];
		$fe = strftime("%Y-%m-%d %H:%M");
		$ru = $GLOBALS['ruta_upload'] . '/' . $tb . '/' . $_SESSION['us_riesgo'] . '/';
		$fi = $ru . $fe . '.csv';
		$ar = str_replace($GLOBALS['ruta_upload'], '', $fi);
		if (!is_dir($ru))
			mkdir($ru);
		if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $fi))
			echo "Error " . $_FILES['archivo']['error'] . " " . $fi;
		else {
			/* echo $cabecera;
			echo "Archivo <b>" . $_POST['b'] . "</b>" . $ar . "<br>";
			echo "<center>";
			echo "<div id='progress-ordennovedadvalor'></div>";
			$GLOBALS['def_' . $tb] = define_objeto($tb, $_REQUEST['d']);
			if (isset($GLOBALS['def_' . $tb]))
				importar($tb, $fi, $_REQUEST['d']);
			echo "<input type=button value='Continuar' OnClick=\"retornar('" . $cr . "','" . $ar . ".csv')\" >";
			echo "</center>"; */
		}
		break;
}

function header_csv($a) {
  $now = gmdate("D, d M Y H:i:s");
  header("Expires:".$now);
  header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
  header("Last-Modified: {$now} GMT");
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: application/download");
  header("Content-Disposition: attachment;filename={$a}");
  header("Content-Transfer-Encoding: binary");
  header("Content-Type: text/csv; charset=UTF-8");
}





function csv($a,$b,$tot= null){
  $df=fopen("php://output", 'w');
  ob_start();
  if(isset($a)){fwrite($df, "\xEF\xBB\xBF"); fputcsv($df,array_keys($a),'|');}
  if(isset($b)){
    foreach ($b as $row) fputcsv($df,$row,'|');
  }
  if ($tot !== null) {
    fwrite($df, "Total Registros: " . $tot . PHP_EOL);
  }
  fclose($df);
  return ob_get_clean();
}

function cleanTxt($val) {
  $val = trim($val);
  $val = addslashes($val);
  $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
  $pattern = '/[\'";\x00-\x1F\x7F]/';
  $replacement = '';
  $val = preg_replace($pattern, $replacement, $val);
  $val = str_replace(array("\n", "\r", "\t"), ' ', $val);
  $val=strtoupper($val);
  return $val;
}

function cleanTx($val) {
  $val = trim($val);
  $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');//maneja las inyecciones XSS
  $pattern = '/[^\w\s\.\-@:]/'; //permitimos alfanuméricos, espacios, puntos, guiones y arroba, 2 puntos
  $val = preg_replace('/\s+/', ' ', $val); // Remover múltiples espacios
  $val = preg_replace($pattern, '', $val); // Quitar caracteres no permitidos
  $val = str_replace(array("\n", "\r", "\t"), '', $val); // Eliminar saltos de línea y tabulaciones
  return $val;
}


function fechas_app($modu){
    switch ($modu) {
    case 'vsp':
      $sql="SELECT valor FROM `catadeta` WHERE idcatalogo='224' and estado='A' and idcatadeta=1;";
      $info=datos_mysql($sql);
      $dias=$info['responseResult'][0]['valor'];
    break;
    case 'vivienda':
      $sql="SELECT valor FROM `catadeta` WHERE idcatalogo='224' and estado='A' and idcatadeta=2;";
      $info=datos_mysql($sql);
      $dias=$info['responseResult'][0]['valor'];
    break;
    default:
      $dias=-7;
      break;
  }
  return intval($dias);
}


function datos_mysql($sql,$resulttype = MYSQLI_ASSOC, $pdbs = false){
		$arr = ['code' => 0, 'message' => '', 'responseResult' => []];
    $con = $GLOBALS['con'];
  if (!$con) {
      die(json_encode(['code' => 30, 'message' => 'Connection error']));
  }
	try {
		$con->set_charset('utf8');
		$rs = $con->query($sql);
		fetch($con, $rs, $resulttype, $arr);
	} catch (mysqli_sql_exception $e) {
		die(json_encode(['code' => 30, 'message' => 'Error BD', 'errors' => ['code' => $e->getCode(), 'message' => $e->getMessage()]]));
	}finally {
    // $GLOBALS['con']->close();
  }
	return $arr;
}

function dato_mysql($sql, $resulttype = MYSQLI_ASSOC, $pdbs = false) {
  $arr = ['code' => 0, 'message' => '', 'responseResult' => []];
  $con = $GLOBALS['con'];
  $con->set_charset('utf8');

  try {
      if (strpos($sql, 'DELETE') !== false) {
          $op = 'Eliminado';
      } elseif (strpos($sql, 'INSERT') !== false) {
          $op = 'Insertado';
      } else {
          $op = 'Actualizado';
      }

      if (!$con->query($sql)) {
          $err = $con->error;
          $con->query("ROLLBACK;");
          if ($con->error == '') {
              $rs = "Error: " . $err;
          } else {
              $rs = "Error: " . $err . " Ouchh! NO se modificó ningún registro, por favor valide la información e intente nuevamente.";
          }
      } else {
          if ($con->affected_rows > 0) {
              $rs = "Se ha " . $op . ": " . $con->affected_rows . " Registro Correctamente.";
          } else {
              $rs = "Ouchh!, NO se ha " . $op . ", por favor valide la información e intente nuevamente.";
          }
      }
  } catch (mysqli_sql_exception $e) {
      $rs = "Error = " . $e->getCode() . " " . $e->getMessage();
  }
  return $rs;
}

function params($campos) {
  $params = [];
  foreach ($campos as $campo) {
      if (isset($_POST[$campo])) {
          $params[] = array('type' => is_numeric($_POST[$campo]) ? 'i' : 's', 'value' => $_POST[$campo]);
      } else {
          $params[] = array('type' => 's', 'value' => '');// Manejar el caso donde el campo no está presente
      }
  }
  return $params;
}

function mysql_prepd($sql, $params) {
  $arr = ['code' => 0, 'message' => '', 'responseResult' => []];
  $con = $GLOBALS['con'];
  $con->set_charset('utf8');
  try {
      $stmt = $con->prepare($sql);
      if ($stmt) {
          $types = '';
          $values = [];
          foreach ($params as $param) {
              $type = $param['type'];// Validar el tipo de parámetro
              $value = ($type === 's') ? cleanTx(strtoupper($param['value'])) : cleanTx($param['value']);// Limpiar el valor dependiendo del tipo
              $types .= ($type === 'z') ? 's' : $type; // // Agregar el tipo correspondiente a $types
              $values[] = $value;// Agregar el valor limpio al array $values
          }
          // var_dump($values);
          $stmt->bind_param($types, ...$values);
          if (!$stmt->execute()) {
              $rs = "Error al ejecutar la consulta: " . $stmt->error . " | SQL: " . $github;
          } else {
              $sqlType = strtoupper($sql);
              if (strpos($sqlType, 'DELETE') !== false) {
                  $op = 'Eliminado';
              } elseif (strpos($sqlType, 'INSERT') !== false) {
                  $op = 'Insertado';
              } elseif (strpos($sqlType, 'UPDATE') !== false) {
                  $op = 'Actualizado';
              } else {
                  $op = 'Operación desconocida';
              }
              $affected = $stmt->affected_rows;
              if ($affected > 0) {
                  $rs = "Se ha " . $op . ": " . $affected . " registro(s) correctamente.";
              } else {
                  $rs = "No se afectaron registros con la operación: " . $op;
              }
          }
          $stmt->close();
        } else {
          $rs = "Error preparando la consulta: " . $con->error . " | SQL: " . $sql;
      }
  } catch (mysqli_sql_exception $e) {
      $rs = "Error = " . $e->getCode() . " " . $e->getMessage();
  }

  return $rs;
}



function fetch(&$con, &$rs, $resulttype, &$arr) {
	if ($rs === TRUE) {
		$arr['responseResult'][] = ['affected_rows' => $con->affected_rows];
	}else {
		if ($rs === FALSE) {
			die(json_encode(['code' => $con->errno, 'message' => $con->error]));
		}
		while ($r = $rs->fetch_array($resulttype)) {
			$arr['responseResult'][] = $r;
		}
		$rs->free();
	}
	return $arr;
}

function panel_content($data_arr,$obj_name,$rp = 20,$no = array('R')) {
	$rta = "";
	$pg = si_noexiste('pag-'.$obj_name,1);
	$rta.= "<table class='tablesorter' cellspacing=0>";
	if($data_arr!=[]){
		$numeroRegistros = count($data_arr);
		$np = floor(($numeroRegistros - 1) / $rp + 1);
		$ri = ($pg - 1) * $rp;
		$rta.= "<thead>";
		foreach ($data_arr[0] as $key => $cmp) {
			if (!in_array($key,$no)) {
				$rta.= "<th>".$key."</th>";
			}
		}	
		$rta.= "</thead id='".$obj_name."_cab'>";
		$rta.= "<tbody id='".$obj_name."_fil'>";
		for($idx=$ri; $idx<=($ri + $rp); $idx++){
			if(!isset($data_arr[$idx])){
				break;
			}
			$r = $data_arr[$idx];
			$rta.= "<tr ".bgcolor($obj_name,$r,"r")." >";
			foreach ($data_arr[0] as $key => $cmp) {
				if (!in_array($key,$no)) {
					$rta.= "<td data-tit='".$key."' class='".alinea($r[$key])."' ".bgcolor($obj_name,$r,"c").">";
					$rta.= formato_dato($obj_name,$key,$r,$key );
					$rta.= "</td>";
				}
			}
			$rta.= "</tr>\n";
		}
		$nc = count($data_arr[0]);
		if ($numeroRegistros != 1) {
			$rta.= "<tr><td class='resumen' colspan=$nc >".menu_reg($obj_name,$pg,$np,$numeroRegistros)."</td></tr>";
		}
	}
	$rta.= "</tbody>";
	$rta.= "</table>";
	return $rta;
}

function opc_sql($sql,$val,$str=true){
	$rta="<option value class='alerta' >SELECCIONE</option>";
	$con=$GLOBALS['con'];
  // var_dump($con);
	if($con->multi_query($sql)){
	do {
		if ($con->errno == 0) {
			$rs = $con->store_result();
			if ($con->errno == 0) {
				if ($rs != FALSE) {
					while ($r = $rs->fetch_array(MYSQLI_NUM))
						if($r[0]==$val){
							$rta.="<option value='".$r[0]."' selected>".htmlentities($r[1],ENT_QUOTES)."</option>";
						}else{
							$rta.="<option value='".$r[0]."'>".htmlentities($r[1],ENT_QUOTES)."</option>";
						}						
				}
				//~ $con->close();
			}
			//~ $rs->free();
		}
		//~ $con->next_result();//11-01-2020
		} while ($con->more_results() && $con->next_result());
		$rs->free();
	}
	//~ $con->close();
  //$con->close();//16-06-2023
	return $rta;
}




/*i*/
function si_noexiste($a,$b){
  if (isset($_REQUEST[$a]))
	 return $_REQUEST[$a];
  else
	 return $b;
}
function alinea($a){
  if (is_numeric($a)) return 'txt-right';
  elseif (is_numeric(str_replace(",","",$a))) return 'txt-right';
  elseif (strpos($a,'%')>0) return 'txt-right';
  elseif (strlen($a)<=2) return 'txt-center';
  else return 'txt-left';
}
function menu_reg($tb,$pg,$np,$nr){
  $rta="<nav class='menu left'>";
  $rta.="<li class='icono regini' OnClick=\"ir_pagina('".$tb."',1,".$np.");\" ></li>";
  $rta.="<li class='icono pgatra' OnClick=\"ir_pagina('".$tb."',$pg-1,".$np.");\"></li>";
  $rta.="<li class='icono pgsigu' OnClick=\"ir_pagina('".$tb."',$pg+1,".$np.");\"></li>";
  $rta.="<li class='icono regfin' OnClick=\"ir_pagina('".$tb."',$np,".$np.");\"></li>&nbsp;";
  $rta.="<input type='text' class='pagina ".$tb." filtro txt-right' maxlength=5 id='pag-".$tb."' value='".$pg."' 
             Onkeypress=\"return solo_numero(event);\" OnChange=\"ir_pagina('".$tb."',this.value,".$np.");\" > ";
  $rta.="<span><b> DE ".$np." PAGINAS ";
  $rta.="<input type='text' class='pagina txt-right' id='rec-".$tb."' value='".$nr."' disabled >"; 
  $rta.=" REGISTROS</b></span>";
  $rta.="</nav><nav class='menu right'>";
  $rta.="<li class='icono regini' OnClick=\"ir_pagina('".$tb."',1,".$np.");\" ></li>";
  $rta.="<li class='icono pgatra' OnClick=\"ir_pagina('".$tb."',$pg-1,".$np.");\"></li>";
  $rta.="<li class='icono pgsigu' OnClick=\"ir_pagina('".$tb."',$pg+1,".$np.");\"></li>";
  $rta.="<li class='icono regfin' OnClick=\"ir_pagina('".$tb."',$np,".$np.");\"></li>";
  $rta.="</nav>";
  return $rta;
}

function create_table($totalReg, $data_arr, $obj_name, $rp = 20,$mod='lib.php', $no = array('R')) {
  $rta = "";
  $pg = si_noexiste('pag-'.$obj_name, 1);
  $rta .= "<table class='tablesorter' cellspacing=0>";
  if (!empty($data_arr)) {
    $np = ceil(($totalReg) / $rp);
    $ri = ($pg - 1) * $rp;
    $rta .= "<thead>";
    foreach ($data_arr[0] as $key => $cmp) {
        if (!in_array($key, $no)) {
           $rta .= "<th>".$key."</th>";
        }
    }
    $rta .= "</thead id='".$obj_name."_cab'>";
    $rta .= "<tbody id='".$obj_name."_fil'>";
    for ($idx = 0; $idx <= ($ri + $rp); $idx++) {
      if (isset($data_arr[$idx])) {
         $r = $data_arr[$idx];
         $rta .= "<tr ".bgcolor($obj_name, $r, "r")." >";
         foreach ($data_arr[0] as $key => $cmp) {
            if (!in_array($key, $no)) {
               $rta .= "<td data-tit='".$key."' class='".alinea($r[$key])."' ".bgcolor($obj_name, $r, "c").">";
               $rta .= formato_dato($obj_name, $key, $r, $key);
               $rta .= "</td>";
            }
         }
         $rta .= "</tr>\n";
      }
    }
    $nc = count($data_arr[0]);
    if ($totalReg != 1) {
      $rta .= "<tr><td class='resumen' colspan=$nc >".pags_table($obj_name, $pg, $np, $totalReg,$mod)."</td></tr>";
    }
  }
  $rta .= "</tbody>";
  $rta .= "</table>";
  return $rta;
}

function pags_table($tb, $pg, $np, $nr,$mod) {
  $np= ($np>$nr) ? ($np-1) : $np;
  $rta = "<nav class='menu left'>";
  $rta .= "<li class='icono regini' OnClick=\"ir_pag('".$tb."', 1, ".$np.",'".$mod."');\"></li>";
  $rta .= "<li class='icono pgatra' OnClick=\"ir_pag('".$tb."', $pg-1, ".$np.",'".$mod."');\"></li>";
  $rta .= "<li class='icono pgsigu' OnClick=\"ir_pag('".$tb."', $pg+1, ".$np.",'".$mod."');\"></li>";
  $rta .= "<li class='icono regfin' OnClick=\"ir_pag('".$tb."', $np, ".$np.",'".$mod."');\"></li>&nbsp;";
  $rta .= "<input type='text' class='pagina ".$tb." filtro txt-right' maxlength=8 id='pag-".$tb."' value='".$pg."' 
            Onkeypress=\"return solo_numero(event);\" OnChange=\"ir_pag('".$tb."', this.value, ".$np.",'".$mod."');\">";
  $rta .= "<span><b> DE ".$np." PAGINAS ";
  $rta .= "<input type='text' class='pagina txt-right' id='rec-".$tb."' value='".$nr."' disabled>"; 
  $rta .= " REGISTROS</b></span>";
  $rta .= "</nav><nav class='menu right'>";
  $rta .= "<li class='icono regini' OnClick=\"ir_pag('".$tb."', 1, ".$np.");\"></li>";
  $rta .= "<li class='icono pgatra' OnClick=\"ir_pag('".$tb."', $pg-1, ".$np.");\"></li>";
  $rta .= "<li class='icono pgsigu' OnClick=\"ir_pag('".$tb."', $pg+1, ".$np.");\"></li>";
  $rta .= "<li class='icono regfin' OnClick=\"ir_pag('".$tb."', $np, ".$np.");\"></li>";
  $rta .= "</nav>";
  return $rta;
}

  function initializeMail(&$mail, $config) {
    $mail->SMTPDebug = 2;
    $mail->IsSMTP();
    $mail->CharSet = (isset($config['CharSet']) ? $config['CharSet'] : 'UTF-8');
    $mail->SMTPSecure = (isset($config['SMTPSecure']) ? $config['SMTPSecure'] : 'tls');
    $mail->Host = (isset($config['Host']) ? $config['Host'] : 'smtp.gmail.com');
    $mail->Port = (isset($config['Port']) ? $config['Port'] : 587);
    $mail->Username = (isset($config['Username']) ? $config['Username'] : 'gerenciadelainformaciongif@gmail.com');
    $mail->Password = (isset($config['Password']) ? $config['Password'] : 'G3r3nc14+');
    $mail->SMTPAuth = (isset($config['SMTPAuth']) ? $config['SMTPAuth'] : true);
    $mail->IsHTML((isset($config['IsHTML']) ? $config['IsHTML'] : true));
    $mail->From = (isset($config['From']) ? $config['From'] : 'gerenciadelainformaciongif@gmail.com');
    $mail->FromName = (isset($config['FromName']) ? $config['FromName'] : 'Gerencia de la información GIF - SDS');
    $mail->Subject = (isset($config['Subject']) ? $config['Subject'] : 'Correo de gerenciadelainformaciongif@gmail.com');
    $mail->AltBody = (isset($config['AltBody']) ? $config['AltBody'] : 'Utilice un lector de mail apropiado!');
  }
  
  function sendMail($mails, $subject, $body) {
    require_once('../libs/mailer/PHPMailerAutoload.php');
    $mail = new PHPMailer();
    initializeMail($mail, ["Subject" => $subject, "Body" => $body]);
    foreach ($mails as $x) {
      $mail->AddAddress($x);
    }
    $plantilla = "";
    $file = fopen("../libs/plantilla.html", "r");
    while ($buff = fgets($file)) {
      $plantilla .= $buff;
    }
    fclose($file);
    eval("\$mail->Body = \"$plantilla\";");
    if ($mail->Send()) {
      $rta = ["code" => 0, "message" => "Succesfully sent.", "email" => $mail];
    } else {
      $rta = ["code" => 60, "message" => 'Mailer Error, Message could not be sent.', "ErrorInfo" => $mail->ErrorInfo];
    }
    return $rta;
  }

function divide($a){
	$id=explode("_", $a);
	return ($id);
}

function rol($a){ //a=modulo, b=perfil c=componente
	$rta=array();
	$sql="SELECT perfil,componente,crear,editar,consultar,exportar,importar FROM adm_roles WHERE modulo = '".$a."' and perfil = FN_PERFIL('".$_SESSION['us_sds']."') AND componente=FN_COMPONENTE('".$_SESSION['us_sds']."') AND estado = 'A'";
	$data=datos_mysql($sql);
  //print_r($data);
	if ($data && isset($data['responseResult'][0])) {
        $rta = $data['responseResult'][0];
    }
	return $rta;
}

function perfil($a){
	$perf=rol($a);
	// var_dump($perf);
	if (empty($perf['perfil']) || $perf['perfil'] === array()){
		echo "<H1>ACCESO NO AUTORIZADO,PARA {$a} VALIDA TUS PERMISOS CON EL ADMINISTRADOR DEL SISTEMA</H1><div class='messag rtawarn'></div>";
		exit();
		 }
}

function perfil1($a = null) {
  if ($a === null) $a = $_SESSION['us_sds'];
  $per = datos_mysql("SELECT FN_PERFIL({$a}) AS perfil");
  $perfil = $per["responseResult"][0]['perfil'];
  return $perfil;
}

function acceso($a){
  $acc=rol($a);
  // print_r($acc);
  if (!empty($acc['perfil'])){
    return true;
  }else{
    return;
  }
}

/*COMPONENTES*/
class cmp { //ntwplcsdxhvuf
  public $n; //name
  public $t; //type
  public $s; //size
  public $d; //default
  public $w; //div
  public $l; //label
  public $c; //list
  public $x; //regexp
  public $h; //holder
  public $v; //valid
  public $u; //update 
  public $tt; //tittle
  public $ww; //width field
  public $vc;//Validaciones personalizadas
  public $sd;//Select dependientes
  public $so;//Validaciones personalizadas otro evento
  function __construct($n='dato',$t='t',$s=10,$d='',$w='div',$l='',$c='',$x='rgxtxt',$h='..',$v=true,$u=true,$tt='',$ww='col-10',$vc=false,array $sd=array(''),$so=false,$path='lib.php') {
    $this->n=$n; 
    $this->t=$t; 
    $this->w=$w;  
    $this->l=($l==''?$n:$l); 
    $this->c=$c;  
    $this->s=$s;  
    $this->d=$d;  
    $this->x=($x==null?($t=='n'?'rgxdfnum':'rgxtxt'):$x);  
    $this->h=$h;  
    $this->v=$v;       
    $this->u=$u;
    $this->tt=$tt;
    $this->ww=$ww;    
    $this->vc=$vc;    
    $this->sd=$sd;
    $this->so=$so;
    $this->path=$path;
  }
  function put(){    
    switch ($this->t) {
    case 's':
        $b=input_sel($this);
		break;
    case 'o':
		$b=input_opt($this);
		break;    
    case 'a':
        $b=input_area($this);
		break;
	case 'd':
		 $b=input_date($this);
		break;
	case 'e':
		 $b=encabezado($this);
		break;
	case 'l':
		 $b=subtitulo($this);
		break;
	case 'c':
		 $b=input_clock($this);
  case 'm':
      $b=select_mult($this);
		break;
    default:
        $b=input_txt($this);
    }    
    return $b."</div>";
  }
}

function input_sel($a){
  $rta="<div class='campo {$a->w} {$a->ww} borde1 oscuro'><div>{$a->l}</div>";
  $rta.="<select ";
  $rta.=" id='{$a->n}'";
  $rta.=" name='{$a->n}'";  
  $rta.=" class='{$a->w} captura  ";
  $rta.= ($a->v==true) ? 'valido' : '';
  $rta.=" title='{$a->tt}'";
  $rta.=" required onChange=\"";
  if ($a->v) $rta.="valido(this);";
  if ($a->vc!=false) $rta.="{$a->vc}(this);";	
  $rta.="\"";  
  if (!$a->u) $rta.=" disabled='true' ";
  for($i=0;$i<count($a->sd);$i++){
	if ($i==0){
		if ($a->sd[$i]!='') $rta.=" onblur=\"changeSelect('{$a->n}','{$a->sd[$i]}','{$a->path}');";
	}else{
		if ($a->sd[$i]!='') $rta.="changeSelect('{$a->n}','{$a->sd[$i]}','{$a->path}');";
	}
  }
  if ($a->so)$rta.=" OnChange='{$a->so}(this)'";
  $rta.="\"";
  $opc="opc=opc_{$a->c}('$a->d');";
  eval('$'.$opc);
  $rta.=">$opc</select>";	
  return $rta;
}

function select_mult($a){
  $rta="<div class='campo {$a->w} {$a->ww} borde1 oscuro'><div>{$a->l}</div>";
  $rta.="<input type='search' id='{$a->n}' class='mult' placeholder='-- SELECCIONE --' onClick='showMult(this,true);' onSearch='searchMult(this);'>"; 
  $rta.="<select multiple";
  $rta.=" id='f{$a->n}'";
  $rta.=" name='f{$a->n}'";  
  $rta.=" class='{$a->w} captura check mult close ";
  $rta.= ($a->v==true) ? 'valido ' : '';
  if (!$a->u) $rta.="' disabled='true ' ";
  $rta.="' onBlur='showMult(this,false);'";
   $rta.=" required onChange=\"";
  if ($a->v) $rta.="valido(this);";
  if ($a->vc!=false) $rta.="{$a->vc}(this);";	
  $rta.="\"";  
  for($i=0;$i<count($a->sd);$i++){
	  if ($i==0){
		  if ($a->sd[$i]!='') $rta.=" OnChange=\"changeSelect('{$a->n}','{$a->sd[$i]}');";
	  }else{
		  if ($a->sd[$i]!='') $rta.="changeSelect('{$a->n}','{$a->sd[$i]}');";
	  }
  }
  if ($a->so)$rta.=" OnChange='{$a->so}(this)'";
  $rta.="\"";
  $opc="opc=opc_{$a->c}('$a->d');";
  eval('$'.$opc);
  $rta.=">$opc</select>";	
  return $rta;
}

function input_opt($a){
  $rta=($a->ww!='col-9')? "<div class='campo {$a->w} {$a->ww} borde1 oscuro'>" : 
  "<div class=\"campo {$a->w} {$a->ww} borde1 oscuro\" style=\"height:20px;\">";
  $rta.="<div>{$a->l}</div>";
  $rta.=($a->ww=='col-9') ? "<div class=\"chk\" style=\"left: 100%;top:-16px;\">" : "<div class='chk'\">";
  $rta.="<input type='checkbox' ";
  $rta.=" id='{$a->n}'";
  $rta.=" name='{$a->n}'";  
  $rta.=" class='{$a->w} captura ";
  if($a->vc) $rta.="validar";
  $rta.="' title='{$a->tt}'";
  if (!$a->u) $rta.=" readonly ";
  if($a->d=='SI') {
	$rta.=" checked value ='SI'"; 
  }else{
	  $rta.=" value='NO'";   
  }
  $rta.=" Onclick=\"checkon(this);";
   if ($a->vc!=false) $rta.="{$a->vc};";
  $rta.="\"><label for='{$a->n}'></label></div>"; 
  return $rta;	
}

function input_area($a){
  $rta="<div class='campo {$a->w} {$a->ww} borde1 oscuro'><div>{$a->l}</div>";
  $rta.="<textarea ";
  $rta.=" id='{$a->n}'";
  $rta.=" name='{$a->n}'";  
  $rta.=" cols='{$a->s}'";
  $rta.=" title='{$a->tt}'";  
  $rta.=" class='{$a->w} ".($a->v?'valido':'')." ".($a->u?'captura':'bloqueo')." '";
  if (!$a->u) $rta.=" readonly ";
  if ($a->v) $rta.=" required onblur=\"valido(this);\" ";
  $rta.=" onkeypress='countMaxChar(this);' Style='width:95%;'";
  $rta.=">".$a->d;
  $rta.="</textarea>";
  return $rta;	
}

function input_txt($a){
  $rta="";
  $t=($a->t=='h'?'hidden':'text');
  if ($a->t!='h') $rta="<div class='campo {$a->w} {$a->ww} borde1 oscuro'><div>{$a->l}</div>";  
  if ($a->t=='fhms') {$a->x='rgxdatehms';$a->h='YYYY-MM-DD HH:MM:SS';$a->s=19;}
  if ($a->t=='fhm')  {$a->x='rgxdatehm';$a->h='YYYY-MM-DD HH:MM';$a->s=16;}
  if ($a->t=='hm')   {$a->x='rgxtime';$a->h='HH:MM';$a->s=5;}
  if ($a->t=='f')    {$a->x='rgxdate';$a->h='YYYY-MM-DD';$a->s=10;}
  $rta.="<input type='$t' ";
  $rta.=" id='{$a->n}'";
  $rta.=" name='{$a->n}'";  
  $rta.=" maxlength='{$a->s}'";  
  $rta.=" title='{$a->tt}'";
  $rta.=" pattern='{$a->x}'";  
  $rta.=" class='{$a->w} ".($a->v?'valido':'')." ".($a->u?'captura':'bloqueo')." ".($a->t=='t'?'':'txt-right')."'";
  if (!$a->u) $rta.=" readonly ";
  if ($a->t!='h') {
      $rta.=" required ";
	  $rta.=" onblur=\"";	  
	  if ($a->v) $rta.="valido(this);";
	  if ($a->x!='') $rta.="solo_reg(this,{$a->x});";
	  if ($a->vc!=false) $rta.="{$a->vc}(this);";	  	  
	  $rta.="\"";
  }	  
  if ($a->t=='n') $rta.="onkeypress=\"return solo_numero(event);\" ";
  if ($a->t=='sd') $rta.="onkeypress=\"return solo_numeroFloat(event);\" ";
  if (strpos($a->t,'f')>-1) $rta.="onkeypress=\"return solo_fecha(event);\" ";    
  if ($a->c!='') $rta.=" list='lista_{$a->c}'"; 
  if ($a->h!='') $rta.=" placeholder='{$a->h}'"; 
  $rta.=" value=\"{$a->d}\" ";
  // $rta.=" onfocus=\"evalue(this);\" ";
  $rta.=">";
  return $rta;	
}  

function input_date($a) {
  $name = htmlspecialchars($a->n, ENT_QUOTES, 'UTF-8');
  $label = htmlspecialchars($a->l, ENT_QUOTES, 'UTF-8');
  $title = htmlspecialchars($a->tt, ENT_QUOTES, 'UTF-8');
  $value = htmlspecialchars($a->d, ENT_QUOTES, 'UTF-8');
  $rta = "<div class='campo {$a->w} {$a->ww} borde1 oscuro'><div>{$label}</div>";
  $rta .= "<input type='date' ";
  $rta .= " id='{$name}'";
  $rta .= " name='{$name}'";
  $rta .= " class='{$a->w} " . ($a->v ? 'valido' : '') . " " . ($a->u ? 'captura' : 'bloqueo') . " " . ($a->t == 't' ? '' : 'txt-right') . "'";
  $rta .= " title='{$title}'";
  if ($a->vc != false) $rta .= "onfocus=\"{$a->vc};\"";
  if ($a->so != false) $rta .= "onchange=\"{$a->so};\"";
  if (!$a->u) $rta .= " readonly ";
  if ($value != '') $rta .= " value=\"{$value}\" ";
  $rta .= ">";
  return $rta;
}

function input_clock($a){
  $name = htmlspecialchars($a->n, ENT_QUOTES, 'UTF-8');
  $label = htmlspecialchars($a->l, ENT_QUOTES, 'UTF-8');
  $title = htmlspecialchars($a->tt, ENT_QUOTES, 'UTF-8');
  $value = htmlspecialchars($a->d, ENT_QUOTES, 'UTF-8');
  $rta = "<div class='campo {$a->w} {$a->ww} borde1 oscuro'><div>{$label}</div>";
  $rta .= "<input type='time' ";
  $rta .= " id='{$name}'";
  $rta .= " name='{$name}'";
  $rta .= " class='{$a->w} " . ($a->v ? 'valido' : '') . " " . ($a->u ? 'captura' : 'bloqueo') . " " . ($a->t == 't' ? '' : 'txt-right') . "'";
  $rta .= " title='{$title}'";
  if ($a->vc != false) $rta .= "onfocus=\"{$a->vc};\"";
  if ($a->so != false) $rta .= "onchange=\"{$a->so};\"";
  if (!$a->u) $rta .= " readonly ";
  if ($value != '') $rta .= " value=\"{$value}\" ";
  $rta .= ">";
  return $rta;
}

function encabezado($a){
  $rta="<div class='encabezado {$a->n}'>{$a->d}<div class='text-right'><li class='icono desplegar-panel' id='{$a->n}' title='ocultar' onclick=\"plegarPanel('{$a->w}','{$a->n}');\"></li></div></div>";
  return $rta;	
}

function subtitulo($a){
 $rta="<div class='subtitulo {$a->n}'>{$a->d}</div>";
  return $rta;	
}

//~ <input class='captura valido agendar' type='date' id='fecha_atenc' name='fecha_atenc' value=".$hoy." min='".$hoy."' max='3000-01-01' required></div>";
?>



