<?php 
session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}
include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}

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
    $verificacao = verificar_usuario_devolucao($recurso, $devolvente);
    if($verificacao == true)
    {
        $tudo_certo = insere_reserva_devolucao($devolvente, $recurso, $data_hora, $hora, "D");
        $msg = 'Recurso devolvido com Sucesso!';
        $msg_resp = 'sucesso';
    }else
    {
        $msg = 'Devolução ínvalida por retirantes diferente!';   
    }
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
