<?php



function data_reservas($cod_reserva){
    $datas_reservas = listar_datas($cod_reserva);
    $datas_reserva = '';
    foreach ($datas_reservas as $dias){
        $datas_reserva.= $dias['data'].' dás '.$dias['hora_inicial'].' ás '.$dias['hora_final'].'<br>';


    }
    return $datas_reserva;
    
}


function tabela_reserva($reservas){

    $reservas_adicionadas = [];
    $conteudo_reservas = '';
    $pri = -1;
    foreach ($reservas as $reserva) {

        $cod_reserva = $reserva['codigo'];
        $recurso = $reserva['recurso'];
        $usuario =  $reserva['usuario'];

        $repetido = ($cod_reserva == $pri);

        if(!$repetido and !in_array($cod_reserva, $reservas_adicionadas) ) {
            
            $data_hoarios = data_reservas($cod_reserva);
            $reservas_adicionadas[] = $cod_reserva;

            if (mb_strlen($data_hoarios) > 40)
            {
                $resumo = substr($data_hoarios, 0, 41);
                $data_hoarios = '<span class="resumo">'.$resumo.'...</span>
                <span class="completo" style="display: none;">
                    '.$data_hoarios.'
                </span>
                <span class="toggle" onclick="alternarConteudo(this)" style="color: blue; cursor: pointer;">Todas as datas</span>';
            }
            $acao = '
            <input type="hidden" name="codigo_da_reserva" value="'.$reserva['codigo'].'">
            <input  class="btn btn-outline-danger" type="submit" value="Apagar"  name="apagar">';
            $conteudo_reservas .= '<tr>';
            $conteudo_reservas .= "<td> $recurso</td>";
            $conteudo_reservas .= "<td> $usuario</td>";
            $conteudo_reservas .= "<td> $data_hoarios</td>";
            $conteudo_reservas .= "<td>$acao </td>";
            $conteudo_reservas .= '</tr>';

        }

        $pri = (!$repetido)?$cod_reserva:$pri;

        

    }
    return $conteudo_reservas;


}



function tabela_reserva_filtrada($reservas){
    $conteudo_reservas = '';
    foreach ($reservas as $reserva) {

        $recurso = $reserva['recurso'];
        $usuario =  $reserva['usuario'];
        $data_hoarios = $reserva['data'].' das '.$reserva['hora_inicial'].' ás '.$reserva['hora_final'];
        $acao = '
        <input type="hidden" name="codigo_da_reserva" value="'.$reserva['codigo'].'">
        <input  class="btn btn-outline-danger" type="submit" value="Apagar" name="apagar">';
        $conteudo_reservas .= '<tr>';
        $conteudo_reservas .= "<td> $recurso</td>";
        $conteudo_reservas .= "<td> $usuario</td>";
        $conteudo_reservas .= "<td> $data_hoarios</td>";
        $conteudo_reservas .= "<td>$acao </td>";
        $conteudo_reservas .= '</tr>';

    }
    return $conteudo_reservas;


}



include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_reserva', 2);
include_once 'Model/mReserva.php';
$id = 'danger';
$msg = '';


include 'Model/mUsuario.php';
include 'Model/mRecurso.php';
$usuarios = listar_usuarios();
$recusos = Carregar_recursos_dados();

$reservas = carregar_filtragem(null, null, null, null );
$conteudo_reservas = tabela_reserva($reservas);

$data_ini = '';
$data_fim = '';

$usua = '';
$recu = '';



$msg = (!isset($_GET['codigo_da_reserva']) and count($reservas)==0)?'Não há reservas no momento.':$msg;


if (isset($_GET['apagar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_reserva', 2);

    $cod_reserva = $_GET['codigo_da_reserva']; 
    $msg_id = apagar_reserva($cod_reserva);
    $id = ($msg_id==0)?'success':'danger';
    $msg = ['Reseva apagada com sucesso', 'Esta reserva não pode ser excluída, pois o recurso já foi retirado.','Essa reserva não pode ser apagada por esta sendo referenciada no ensalamento.'];    
    $recu = (filter_var($_GET['recurso'], FILTER_VALIDATE_INT) !== false)?$_GET['recurso']:null;
    $usua = (filter_var($_GET['usuario'], FILTER_VALIDATE_INT) !== false)?$_GET['usuario']:null;
    $data_ini = (mb_strlen($_GET['p_ini']) == 10)?$_GET['p_ini']:null;
    $data_fim = (mb_strlen($_GET['p_fim']) == 10)?$_GET['p_fim']:null;
     if ($data_ini != null and $data_fim != null and new DateTime($data_ini) > new DateTime($data_fim)){
        $data_ini = null;
        $data_fim = null;

    }
    $reservas = carregar_filtragem($recu, $usua, $data_ini, $data_fim );

    $condi = ($recu == null and $usua== null and $data_fim == null and $data_ini==null);
    $conteudo_reservas = ($condi)?tabela_reserva($reservas):tabela_reserva_filtrada($reservas);
    $msg = $msg[$msg_id];
}elseif (isset($_GET['filtra'])) {

    $recu = (filter_var($_GET['recurso'], FILTER_VALIDATE_INT) !== false)?$_GET['recurso']:null;
    $usua = (filter_var($_GET['usuario'], FILTER_VALIDATE_INT) !== false)?$_GET['usuario']:null;
    $data_ini = (mb_strlen($_GET['p_ini']) == 10)?$_GET['p_ini']:null;
    $data_fim = (mb_strlen($_GET['p_fim']) == 10)?$_GET['p_fim']:null;


    if ($data_ini != null and $data_fim != null and new DateTime($data_ini) > new DateTime($data_fim)){
        $msg = 'Data limite ínvalida. Informe uma data limite igual ou superior a data inicial. ';
        $id = 'danger';
        $data_ini = '';
        $data_fim = '';

    }else{
        $reservas = carregar_filtragem($recu, $usua, $data_ini, $data_fim );
        $msg =(count($reservas)==0)?'Nem uma reserva encontrada.':'';
        $id = 'danger';
        
        $condi = ($recu == null and $usua== null and $data_fim == null and $data_ini==null);
        $conteudo_reservas = ($condi)?tabela_reserva($reservas):tabela_reserva_filtrada($reservas);
        
        

    }
    
}




$data_ini = ($data_ini != null)?$data_ini:'';
$data_fim = ($data_fim != null)?$data_fim:'';
$usuarios = mandar_options($usuarios, $usua);
$recusos = mandar_options($recusos, $recu);
// Carregar o HTML do arquivo de visualização
$html = file_get_contents('View/vReservas.php');
$html = str_replace('{{Reservas}}', $conteudo_reservas, $html);
$html = str_replace('{{mensagem}}', $msg, $html);
$html = str_replace('{{retorno}}', $id, $html);
$html = str_replace('{{recursos}}', $recusos, $html);
$html = str_replace('{{usuario}}', $usuarios, $html);
$html = str_replace('{{data_ini}}', $data_ini, $html);
$html = str_replace('{{data_fim}}', $data_fim, $html);

echo $html;
?>




