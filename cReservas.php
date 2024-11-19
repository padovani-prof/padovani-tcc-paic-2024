<?php
include_once 'Model/mReserva.php';

if (isset($_GET['codigo_da_reserva'])) {
    $cod_reserva = $_GET['codigo_da_reserva']; 
    apagar_reserva($cod_reserva);
}

$reservas = listar_reserva();
$conteudo_reservas = '<tbody>';

$men = ''; 

foreach ($reservas as $reserva) {
    $datas_reserva = listar_datas($reserva["codigo_reserva"]); 

    $datas_horarios = '';
    foreach ($datas_reserva as $data) {
        $datas_horarios .= $data["data"] . ' (' . $data["hora_inicial"] . '-' . $data["hora_final"] . ')<br>';
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

$conteudo_reservas .= '</tbody>';

// Carregar o HTML do arquivo de visualização
$html = file_get_contents('View/vReservas.php');
$html = str_replace('{{Reservas}}', $conteudo_reservas, $html);
$html = str_replace('{{mensagem}}', $men, $html);
echo $html;
?>