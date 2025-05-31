<?php



function tabela_reserva($reservas){


    $conteudo_reservas = '';
    foreach ($reservas as $reserva) {

        $recurso = $reserva['recurso'];
        $usuario =  $reserva['usuario'];
        $data_hoarios = $reserva['data'].' das '.$reserva['hora_inicial'].' ás '.$reserva['hora_final'];
        $acao = '<form action="cReservas.php">
        <input type="hidden" name="codigo_da_reserva" value="'.$reserva['codigo'].'">
        <input  class="btn btn-outline-danger" type="submit" value="Apagar"></form>';
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



$msg = (!isset($_GET['codigo_da_reserva']) and count($reservas)==0)?'Não há reservas no momento.':$msg;


if (isset($_GET['codigo_da_reserva'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_reserva', 2);

    $cod_reserva = $_GET['codigo_da_reserva']; 
    $msg_id = apagar_reserva($cod_reserva);
    $id = ($msg_id==0)?'success':'danger';
    $msg = ['Reseva Apagada com Sucesso', 'Esta reserva não pode ser excluída, pois o recurso já foi retirado.','Essa reserva não pode ser apagada por esta sendo Referenciada no Ensalamento.'];
    $reservas = carregar_filtragem(null, null, null, null );
    $msg = $msg[$msg_id];
}elseif (isset($_GET['filtra'])) {

    $recu = (filter_var($_GET['recurso'], FILTER_VALIDATE_INT) !== false)?$_GET['recurso']:null;
    $usua = (filter_var($_GET['usuario'], FILTER_VALIDATE_INT) !== false)?$_GET['usuario']:null;
    $data_ini = (mb_strlen($_GET['p_ini']) == 10)?$_GET['p_ini']:null;
    $data_fim = (mb_strlen($_GET['p_fim']) == 10)?$_GET['p_fim']:null;
    $reservas = carregar_filtragem($recu, $usua, $data_ini, $data_fim );

    $msg =(count($reservas)==0)?'Nem uma reserva encontrada.':'';
    $id = 'danger';
    

    
}

$conteudo_reservas = tabela_reserva($reservas);
$usuarios = mandar_options($usuarios);
$recusos = mandar_options($recusos);
// Carregar o HTML do arquivo de visualização
$html = file_get_contents('View/vReservas.php');
$html = str_replace('{{Reservas}}', $conteudo_reservas, $html);
$html = str_replace('{{mensagem}}', $msg, $html);
$html = str_replace('{{retorno}}', $id, $html);

$html = str_replace('{{recursos}}', $recusos, $html);
$html = str_replace('{{usuario}}', $usuarios, $html);
echo $html;
?>


