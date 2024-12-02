<?php 
include 'Model/mFormulariorRetirada.php';
include 'Model/mDisponibilidade.php';
$html = file_get_contents('View/vFormulariorRetirada.php');


$msg = '';
$id_msg = 'erro'; 
if(isset($_GET['btnConfirmar']) and isset($_GET['recurso']))
{
    $recurso = $_GET['recurso'];
    $retirante = $_GET['retirante'];
    date_default_timezone_set('America/Manaus'); 
    $data_atual = new DateTime();

    $data = new DateTime();
    $data = $data->format('Y/m/d');
    $hora_fim = $_GET['hora_final'];
    $data_devolução = new DateTime("$data $hora_fim");

    // dados validos
    if(mb_strlen($hora_fim)==5  and $data_atual < $data_devolução )
    {
        $hora_ini =  $data_atual->format('H:i:s');
        $hora_fim = $hora_fim.':00';
        

        // verificar se o recurso não está reservado
        $disponives = Disponibilidade([$data, $hora_ini, $hora_fim], [], [$recurso]);
        if (count($disponives)>0)
        {
            $data_hora = $data.' '.$hora_ini;
            $tudo_certo = insere_reserva_devolucao($retirante, $recurso, $data_hora, $hora_fim,'R');
            if($tudo_certo== true)
            {
                $msg = 'Recurso Reservado com Sucesso!';
                $id_msg = 'sucesso'; 
            }  
        }else{
            $msg = 'Recurso já foi reservado';

        }
    }else{
        $msg = 'Horário de devolução ínvalido.'; 
    }
    
}

$recursos_reserva = carrega_retirada_disponivel();
$retirantes = listar_usuarios();

$opicoes_recurso = optios($recursos_reserva);
$opicoes_retirantes = optios($retirantes);

$html = str_replace('{{retirante}}', $opicoes_retirantes, $html);
$html = str_replace('{{recursos}}',$opicoes_recurso , $html);
$html = str_replace('{{mensagem}}',$msg, $html);

echo $html;

?>



