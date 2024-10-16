
<?php 
//error_reporting(E_ALL);
//ini_set('display_errors',1);
session_start();
//print_r($_SESSION);
if (isset($_SESSION['codigo_usuario'])){
$usuario = $_GET['usuario']."#".$_SESSION['codigo_usuario']."#";
$html = file_get_contents('View/vMenu.php');
$html = str_replace('{{saudacao}}', 'Bem vindo(a), ' . htmlspecialchars($usuario) . '!', $html);
echo $html;
}else{
    echo "Acesso negado";
}


?>