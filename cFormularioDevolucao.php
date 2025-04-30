<?php 

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc', 2);


include 'Model/mFormulariorRetirada.php';
$html = file_get_contents('View/vFormularioDevolucao.php');
$recursos = carrega_recursos_emprestados();

$msg = (isset($_GET['msg']))?$_GET['msg']:((count($recursos)==0)?'Nomento não temos nem um recurso emprestado.':'');
$msg_resp = (isset($_GET['msg']))?'success':'danger';

$recurso = '';
$devolvente = '';

if(isset($_GET['btnConfirmar']))
{
    $recurso = $_GET['recurso'];
    $devolvente = $_GET['devolvente'];
    if($_GET['recurso']!='NULL' and  $_GET['devolvente'] !='NULL'){
        date_default_timezone_set('America/Manaus'); 
        $data_atual = new DateTime();
        $data_hora = $data_atual->format('Y/m/d H:i:s');
        $hora = $data_atual->format('H:i');
        $verificacao = verificar_usuario_devolucao($recurso, $devolvente);
        if($verificacao == true)
        {
            $tudo_certo = insere_reserva_devolucao($devolvente, $recurso, $data_hora, $hora, "D");
            $msg = 'Recurso devolvido com Sucesso!';
            header("Location: cFormularioDevolucao.php?msg=$msg");
            exit();
        }else
        {
            $msg = 'Devolução inválida, pois o retirante não é a mesma pessoa que está devolvendo.';   
        }
    }
    else {
        $msg = 'Por favor adicione todas as informações.';
    }
}


$devolventes = listar_usuarios();
$recursos = mandar_options($recursos, $recurso);
$devolventes = mandar_options($devolventes, $devolvente);
$html = str_replace('{{devolvente}}', $devolventes, $html);
$html = str_replace('{{recursos}}', $recursos, $html);
$html = str_replace('{{retorno}}', $msg_resp, $html);
$html = str_replace('{{mensagem}}', $msg, $html);

echo $html;

?>
