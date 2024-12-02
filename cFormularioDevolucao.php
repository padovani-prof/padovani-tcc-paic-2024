<?php 
include 'Model/mFormulariorRetirada.php';
$html = file_get_contents('View/vFormularioDevolucao.php');

$msg = '';
$msg_resp = 'erro';
if(isset($_GET['btnConfirmar']) and isset($_GET['recurso']))
{

    $recurso = $_GET['recurso'];
    $devolvente = $_GET['devolvente'];
    date_default_timezone_set('America/Manaus'); 
    $data_atual = new DateTime();
    $data_hora = $data_atual->format('Y/m/d H:i:s');
    $hora = $data_atual->format('H:i');
    $tudo_certo = insere_reserva_devolucao($devolvente, $recurso, $data_hora, $hora, "D");
    $msg = 'Recurso devolvido com Sucesso!';
    $msg_resp = 'sucesso';


}





$recursos = carrega_recursos_emprestados();
$devolventes = listar_usuarios();
$recursos = optios($recursos);
$devolventes = optios($devolventes);
$html = str_replace('{{devolvente}}', $devolventes, $html);
$html = str_replace('{{recursos}}', $recursos, $html);
$html = str_replace('{{retorno}}', $msg_resp, $html);
$html = str_replace('{{mensagem}}', $msg, $html);

echo $html;

?>
