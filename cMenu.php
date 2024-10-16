
<?php 
$usuario = $_GET['usuario'];
$html = file_get_contents('View/vMenu.php');
$html = str_replace('{{saudacao}}', 'Bem vindo(a), ' . htmlspecialchars($usuario) . '!', $html);
echo $html;



?>