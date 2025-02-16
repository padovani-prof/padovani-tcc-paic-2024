<?php 

include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc', 2);


include 'Model/mFormulariorRetirada.php';
$html = file_get_contents('View/vFormularioDevolucao.php');
$recursos = carrega_recursos_emprestados();

$msg = (count($recursos)==0)?'Nomento não temos nem um recurso emprestado.':'';
$msg_resp = 'erro';

if(isset($_GET['btnConfirmar']))

{
        if($_GET['recurso']!='NULL' and  $_GET['devolvente'] !='NULL')
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
    else {
        $msg = 'Por favor preenxa todos os dados.';
    }
}


$devolventes = listar_usuarios();
$recursos = optios($recursos);
$devolventes = optios($devolventes);
$html = str_replace('{{devolvente}}', $devolventes, $html);
$html = str_replace('{{recursos}}', $recursos, $html);
$html = str_replace('{{retorno}}', $msg_resp, $html);
$html = str_replace('{{mensagem}}', $msg, $html);

echo $html;

?>
