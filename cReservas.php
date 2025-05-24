<?php


include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_reserva', 2);
include_once 'Model/mReserva.php';
$id = 'danger';
$msg = '';
if (isset($_GET['codigo_da_reserva'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_reserva', 2);

    $cod_reserva = $_GET['codigo_da_reserva']; 
    $msg_id = apagar_reserva($cod_reserva);
    $id = ($msg_id==0)?'success':'danger';
    $msg = ['Reseva Apagada com Sucesso', 'Esta reserva não pode ser excluída, pois o recurso já foi retirado.','Essa reserva não pode ser apagada por esta sendo Referenciada no Ensalamento.'];
    $msg = $msg[$msg_id];
}
$reservas = listar_reserva();
$conteudo_reservas = '';


$msg = (!isset($_GET['codigo_da_reserva']) and count($reservas)==0)?'Não há reservas no momento.':$msg;


foreach ($reservas as $reserva) {
    $datas_reserva = listar_datas($reserva["codigo_reserva"]); 
    $datas_horarios = '';
    foreach ($datas_reserva as $data) {
        // Formatando a data para "DD/MM/AAAA"
        $data_formatada = DateTime::createFromFormat('Y-m-d', $data["data"])->format('d/m/Y');
        
        // Montando o texto formatado
        $datas_horarios .= $data_formatada . ' das ' . $data["hora_inicial"] . ' às ' . $data["hora_final"] . '<br>';
    }
    $conteudo_reservas .= '<tr>
        <td>' . ($reserva["recurso"]) . '</td>
        <td>' . ($reserva["usuario"]) . '</td>
        <td>' . $datas_horarios . '</td>
        <td>
            <form action="cReservas.php" method:"get">
            
                <input type="hidden" name="codigo_da_reserva" value="' . $reserva["codigo_reserva"] . '">
                <input type="submit" class="btn btn-outline-danger" name="apagar" value="Apagar">
            </form>
        </td>
    </tr>';
}



// Carregar o HTML do arquivo de visualização
$html = file_get_contents('View/vReservas.php');
$html = str_replace('{{Reservas}}', $conteudo_reservas, $html);
$html = str_replace('{{mensagem}}', $msg, $html);
$html = str_replace('{{retorno}}', $id, $html);
echo $html;
?>
