<?php


include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_reserva', 2);


include_once 'Model/mReserva.php';

$id = 'nada';
$msg = '';
if (isset($_GET['codigo_da_reserva'])) {
    $cod_reserva = $_GET['codigo_da_reserva']; 
    $msg = apagar_reserva($cod_reserva);
    $id = ($msg)?'sucesso':'erro';
    $msg = ($msg)?'Reseva apagada com sucesso':'Essa reserva não pode ser apagada por esta sendo referenciada no ensalamento.';

}

$reservas = listar_reserva();
$conteudo_reservas = '';



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
                <input type="submit" name="apagar" value="Apagar">
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
