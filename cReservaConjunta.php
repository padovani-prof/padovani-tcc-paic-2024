<?php

function verificar_reservar($dados){
    foreach($dados as $dado){
        $td_dados = explode(',', $dado);
        if (consta_reserva($td_dados[0], $td_dados[2], $td_dados[3].':00', $td_dados[4].':00')){
            return true;
        }
    }
    return false;
}

function Reserva_conjunta($dados, $agendador, $utilizador, $justific){
    $recu = -1;
    foreach($dados as $dado){
        $td_dados = explode(',', $dado);
        if($recu != $td_dados[0]){
            $id_reserva = insere_reserva($justific, $agendador, $utilizador, $td_dados[0]);
            $recu = $td_dados[0];
        }
        insere_data_reserva($td_dados[2], $td_dados[3].':00', $td_dados[4].':00', $id_reserva);
    }
}

function tabe_html($dados){
    $ht_dados = '';
    $id = -1;
    foreach($dados as $dado){
        $recurso = explode(',', $dado);
        $id_recu = $recurso[0];
        $data = explode('-', $recurso[2]);

        if($id != $id_recu){
            if($id != -1){
                $ht_dados .= '</tr>';
            }
            $ht_dados .= '<tr> <td>'.$recurso[1].'</td> <td>';
            $id = $id_recu;
        } else {
            $ht_dados .= '<br>';
        }

        $ht_dados .= $data[2].'/'.$data[1].'/'.$data[0].'<br>'.$recurso[3].' - '.$recurso[4];
    }
    return $ht_dados;
}

function dados_hidem($dados){
    return '<input type="hidden" name="marcas" value="'.urlencode(json_encode($dados)).'">';
}

function dados_em_lista($dados){
    $peri_data = [[], [], []];
    foreach($dados as $reserva){
        $recurso_v = explode(',', $reserva);
        $peri_data[0][] = $recurso_v[2]; // datas
        $peri_data[0][] = $recurso_v[3]; // horário inicial
        $peri_data[0][] = $recurso_v[4]; // horário final
        $peri_data[1][] = $recurso_v[0]; // código recurso
        $peri_data[2][] = $recurso_v[0].','.$recurso_v[1]; // código+nome
    }
    return $peri_data;
}

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cons_disponibilidade', 2);

include 'Model/mFormulariorRetirada.php';
include 'Model/mReservaConjunta.php';
include 'Model/mDisponibilidade.php';

$dados = json_decode(urldecode($_GET['marcas'])); 
$recu = dados_em_lista($dados);
$re_volta = $recu[2];
$periodos = $recu[0];
$recu = $recu[1];

$voltar = "utilizador=".$_GET['utilizador'].'&periodo='.urlencode(json_encode($periodos)).
          ((count($re_volta) > 0)? "&cate_recu=".urlencode(json_encode($re_volta)) : '');

$marca_ult = '';
$marca_agen = '';
$utilizador = $_GET['utilizador'];
$justific = '';
$msg = '';
$id_msg = 'danger';

if(isset($_GET['reservar'])){
    if(verificar_reservar($dados)){
        header('Location: cFiltroDisponibildade.php');
        exit();
    }

    $agendador = $_GET['agendador'];
    $justific = $_GET['justfc'];
    $marca_agen = $agendador;

    if($agendador == 'NULL'){
        $msg = 'Por favor, selecione o agendador.';
    } else {
        $disponivel = count(Disponibilidade($periodos, [], $recu)) > 0;
        if($disponivel){
            Reserva_conjunta($dados, $agendador, $utilizador, $justific);
            header('Location: cReservas.php');
            exit();
        } else {
            $msg = 'Infelizmente, outro usuário concluiu uma reserva antes de você. Por favor, refaça a consulta para verificar a disponibilidade atual.';
        }
    }
}

$html = file_get_contents('View/vReservaConjunta.php');
$usuarios = listar_usuarios();
$usuarios_agendador = mandar_options($usuarios, $marca_agen);
$hidem_dados = dados_hidem($dados);
$tabe_htm = tabe_html($dados);

$html = str_replace('{{dados}}', $hidem_dados, $html);
$html = cabecalho($html, 'Reserva Conjunta');
$html = str_replace('{{reservas}}', $tabe_htm, $html);
$html = str_replace('{{agendador}}', $usuarios_agendador, $html);
$html = str_replace('{{usuario}}', '<input type="hidden" name="utilizador" value="'.$utilizador.'">', $html);
$html = str_replace('{{just}}', $justific, $html);
$html = str_replace('{{retorno}}', $id_msg, $html);
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{link}}', $voltar, $html);

echo $html;

?>
