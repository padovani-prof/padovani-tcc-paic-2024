<?php 


function optios($dados){
    $opt = '<option value="NULL">...</option>';
    foreach($dados as $dado)
    {
        $opt .= '<option value="'. $dado['codigo'].'">'.mb_strtoupper($dado['nome'] ).'</option>';
    }
    return $opt;

}


function data_em_dia_semana($data){
    $dias = [
        'Sunday' => 1,
        'Monday' => 2,
        'Tuesday' => 3,
        'Wednesday' => 4,
        'Thursday' => 5,
        'Friday' => 6,
        'Saturday' => 7
    ];

    $diaSemana = $dias[date('l', strtotime($data))];
    return $diaSemana;
    


}

include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc', 2);

include 'Model/mFormulariorRetirada.php';
include 'Model/mDisponibilidade.php';
$html = file_get_contents('View/vFormulariorRetirada.php');


$msg = '';
$id_msg = 'erro'; 
$marca_recu = '';
$marca_reti = '';
$mar_hora = '';
if(isset($_GET['btnConfirmar']) and isset($_GET['recurso']))
{
    $recurso = $_GET['recurso'];
    $retirante = $_GET['retirante'];
    $hora_fim = $_GET['hora_final'];
    $marca_recu = $recurso;
    $marca_reti = $retirante;
    $mar_hora = $hora_fim;

    date_default_timezone_set('America/Manaus'); 
    $data_atual = new DateTime();

    $data = new DateTime();
    $data = $data->format('Y/m/d');
    
    $data_devolução = new DateTime("$data $hora_fim");

    // dados validos

    if(mb_strlen($hora_fim)==5  and $data_atual < $data_devolução and $recurso != 'NULL' and $retirante != 'NULL')
    {
        $hora_ini =  $data_atual->format('H:i:s');
        $hora_fim = $hora_fim.':00';


        // verificar permição
        if (verificar_permicao_recurso($data, $hora_ini, $hora_fim, $recurso, $retirante, data_em_dia_semana($data))){
                // verificar se o recurso não está reservado
            $disponives = Disponibilidade([$data, $hora_ini, $hora_fim], [], [$recurso]);
            $disponives = (count($disponives)> 0)? criar_reserva_retirada($retirante, $recurso, $data, $hora_ini, $hora_fim) :false;
            $sua_reserva = verificar_reserva_do_retirante([$data, $hora_ini, $hora_fim], $retirante, $recurso);
            if ($disponives or $sua_reserva)
            {
                $data_hora = $data.' '.$hora_ini;
                $tudo_certo = insere_reserva_devolucao($retirante, $recurso, $data_hora, $hora_fim,'R');
                if($tudo_certo==true)
                {
                    $msg = 'Recurso retirado com Sucesso!';
                    $id_msg = 'sucesso'; 
                    $marca_recu = '';
                    $marca_reti = '';
                    $mar_hora = '';
                }  
            }
            else{
                $msg = 'Esse recurso está reservado para outro úsuario.';

            }

        }else{
            $msg = 'Voçê não possui permição para retirar esse recurso.';
        }

        
        
        
    }else{
        
        $msg = (mb_strlen($hora_fim)!=5  or $recurso == 'NULL' or $retirante == 'NULL')?'Por favor preenxa todos os dados. ':' Horário de devolução ínvalido.'; 
    }
    
}

$recursos_reserva = carrega_retirada_disponivel();
$retirantes = listar_usuarios();

$opicoes_recurso = mandar_options($recursos_reserva, $marca_recu);
$opicoes_retirantes = mandar_options($retirantes, $marca_reti);

$html = str_replace('{{retirante}}', $opicoes_retirantes, $html);
$html = str_replace('{{recursos}}',$opicoes_recurso , $html);
$html = str_replace('{{mensagem}}',$msg, $html);

$html = str_replace('{{hora_fim}}',$mar_hora, $html);



echo $html;
?>



